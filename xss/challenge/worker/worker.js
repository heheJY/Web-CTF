const fs = require('fs');
const md5 = require('md5');
const puppeteer = require('puppeteer');
const Redis = require('ioredis');
const connection = new Redis(6379, 'redis');

const admin_username = "admin";
const admin_password = "w28J0zqqpp7z9Ty8Sl58Z7iEf4h911zZ";

const browser_option = {
    executablePath: '/usr/bin/google-chrome',
    headless: true,
    args: [
        '--no-sandbox',
        '--disable-background-networking',
        '--disable-default-apps',
        '--disable-extensions',
        '--disable-gpu',
        '--disable-sync',
        '--disable-translate',
        '--hide-scrollbars',
        '--metrics-recording-only',
        '--no-first-run',
        '--safebrowsing-disable-auto-update',
    ],
};
let browser = undefined;

const crawl = async (url) => {
    console.log(`[+] Query! (${url})`);
    const page = await browser.newPage();
    try {
        const response = await page.goto(url, { waitUntil: 'networkidle0', timeout: 3000 });
        console.log(`[+] Response status: ${response.status()}`);
        console.log(`[+] Final URL: ${response.url()}`);
        const content = await page.content();
        console.log(`[+] Page Content:\n${content}`);

        const likeButton = await page.$('#like');
        if (likeButton) {
            console.log(`[+] Found #like button. Clicking it...`);
            await likeButton.click();
            console.log(`[+] Clicked #like button.`);
        } else {
            console.log(`[!] #like button not found.`);
        }
    } catch (err) {
        console.log(`[!] Error while crawling: ${err.message}`);
    } finally {
        await page.close();
    }
};

const init = async () => {
    const browser = await puppeteer.launch(browser_option);
    const page = await browser.newPage();
    console.log(`[+] Setting up...`);
    try {
        await page.goto(`http://challenge/login.php`);
        await page.waitForSelector('#username');
        await page.type('#username', admin_username);
        await page.waitForSelector('#password');
        await page.type('#password', admin_password);
        await page.waitForSelector('#login-submit');
        await Promise.all([
            page.$eval('#login-submit', (elem) => elem.click()),
            page.waitForNavigation(),
        ]);
        const body = await page.evaluate(() => document.body.innerHTML);
        if (!body.includes('href="posts.php"')) {
            throw Error(`Login failed at ${page.url()}.`);
        }
        console.log(`[+] Setup done!`);
    } catch (err) {
        console.log(`[-] Error while setting up :(`);
        console.log(err);
    } finally {
        await page.close();
    }
    return browser;
};

function handle() {
    console.log("[+] handle");
    connection.blpop("query", 0, async function (err, message) {
        if (browser === undefined) browser = await init();
        await crawl("http://challenge/post.php?id=" + message[1]);
        setTimeout(handle, 10); // handle next
    });
}
handle(); // first ignite
