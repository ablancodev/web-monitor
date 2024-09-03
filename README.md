# web-monitor
Allows users to obtain performance insights for multiple websites using the Google PageSpeed Insights API. This functionality enables you to easily monitor and optimize the performance of your websites.

## 1.- Get your API KEY
You can get your Google API KEY from here: https://developers.google.com/speed/docs/insights/v5/get-started?hl=es-419

## 2.- Modify the PHP constant
Once you have obtained your API KEY, you need to modify the PHP constant.
Replace the value of `YOUR_API_KEY` with your newly obtained API KEY.

## 3.- Use it
To use the API, make a GET request to `index.php` with the following parameters:
- `url`: A comma-separated list of websites to monitor, e.g., `web_1,web_2,web_3`.
- `strategy`: The strategy to use for monitoring, options are `mobile`, `desktop`

Example usage: `index.php?url=web_1,web_2,web_3&strategy=desktop`


