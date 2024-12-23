# Dockerfile for the worker with Puppeteer and required dependencies

# Use an official Node.js runtime as a parent image
FROM node:18-slim

# Set the working directory
WORKDIR /app

# Install necessary dependencies for Puppeteer and Chrome
RUN apt-get update && apt-get install -y \
    wget \
    gnupg \
    curl \
    ca-certificates \
    fonts-liberation \
    libatk1.0-0 \
    libatk-bridge2.0-0 \
    libcups2 \
    libdbus-1-3 \
    libxcomposite1 \
    libxrandr2 \
    libxss1 \
    libxcursor1 \
    libasound2 \
    libxdamage1 \
    libxinerama1 \
    libappindicator3-1 \
    libnss3 \
    lsb-release \
    libgbm-dev \
    libpangocairo-1.0-0 \
    libpangoft2-1.0-0 \
    && rm -rf /var/lib/apt/lists/*

# Add Google Chrome's repository
RUN wget -q -O - https://dl.google.com/linux/linux_signing_key.pub | apt-key add - \
    && sh -c 'echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" > /etc/apt/sources.list.d/google-chrome.list' \
    && apt-get update \
    && apt-get install -y google-chrome-stable \
    && rm -rf /var/lib/apt/lists/*

# Install Puppeteer and md5 module
RUN npm install puppeteer md5 ioredis

# Copy application files
COPY . /app

# Expose the necessary port (if needed, adjust based on your application)
EXPOSE 3000

# Start the worker
CMD ["node", "worker.js"]