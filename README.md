# Landing API

A lightweight PHP-based API service for handling OTP authentication and user operations. Built with custom routing, middleware support, and Swagger integration.

## 📦 Requirements

- PHP ^7.4 or ^8.0
- Composer
- PDO & JSON extensions
- [Firebase JWT](https://github.com/firebase/php-jwt)
- [phpdotenv](https://github.com/vlucas/phpdotenv)
- [Swagger-PHP](https://github.com/zircote/swagger-php)
- [PHP-DI](https://php-di.org/)

## 🚀 Installation

### 1. Clone the repository
```bash
git clone https://github.com/your-username/landing.git
```
```bash
cd landing
```

### 2. Install dependencies:
```bash
composer install
```
## 🧪 Run Development Server
```bash
composer start
```
## 📚 API Documentation

http://localhost:8000/swagger

## 📱 Phone Verification Flow with Evina Fraud Protection
This project integrates Evina's fraud detection system during the phone number authentication process. Below is an overview of how the front-end and back-end interact to securely handle OTP verification:

### 📂 public/script.js

#### Generates a unique Evina request ID:

```js
let evinaRequestId = (() =>
  'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, (c) => {
    let r = Math.random() * 16 | 0,
        v = c === 'x' ? r : (r & 0x3 | 0x8);
    return v.toString(16);
  })
)();
```

#### Fetches Evina's fraud protection script and dynamically injects it into the page:

```js
const evinaUrl = `https://ksg.intech-mena.com/MSG/v1.1/API/GetScript?...&requestId=${evinaRequestId}`;
fetch(evinaUrl)
.then(response => response.json())
.then(result => {
  const fraudScript = document.createElement('script');
  fraudScript.text = result["100"];
  document.head.prepend(fraudScript);
  document.dispatchEvent(new Event('DCBProtectRun'));
});
```

#### Handles the Continue button click:

- Validates the phone number

- Sends a POST request to /api/v1/auth/send-code

- Passes evinaRequestId to the backend for fraud monitoring

### 🧩 api/v1/controllers/AuthController.php

- This controller contains two key methods:

- sendCode(Request $req, Response $res)
- Validates the phone and evinaRequestId.

- Sends a request to a 3rd-party API (fordragopro.com) to trigger OTP delivery.

- Includes Evina data and other parameters like userIP, userUA, and clickid.

- verifyCode(Request $req, Response $res)
- Validates phone, OTP code, session ID, and evinaRequestId.

- Sends verification data to the same 3rd-party API.

- Returns success or error response based on OTP match.
