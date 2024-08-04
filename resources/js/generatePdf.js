const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

async function generatePDF() {
    const htmlPath = process.argv[2];
    const outputFilename = process.argv[3];

    const browser = await puppeteer.launch({
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });
    const page = await browser.newPage();

    await page.goto(`file://${htmlPath}`, { waitUntil: 'networkidle0' });

    const pdf = await page.pdf({
        format: 'A4',
        printBackground: true
    });

    const outputPath = path.join('/tmp', outputFilename);
    fs.writeFileSync(outputPath, pdf);

    await browser.close();

    console.log(`PDF saved to ${outputPath}`);
}

generatePDF().catch(console.error);

module.exports = { generatePDF };
