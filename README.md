# Gcilishe Taliwe Classic Funerals Website

A responsive one-page funeral service website built for a South African funeral business. The website presents the company’s funeral services, funeral packages, funeral insurance information, frequently asked questions, contact details, WhatsApp click-to-chat support, and an enquiry form.

This project is designed as part of a web development portfolio to demonstrate responsive frontend development, Bootstrap 5 layout skills, Tailwind CSS styling, JavaScript interactivity, SEO setup, and basic backend email integration using PHPMailer.

---

## Project Overview

**Gcilishe Taliwe Classic Funerals** is a professional funeral service website focused on providing a calm, respectful, and trustworthy online presence for families seeking funeral assistance in South Africa.

The website is structured as a single-page responsive landing page. Users can navigate directly to each section using the top navigation menu.

---

## Website Sections

The website includes the following sections:

- **Home**  
  Hero section with a calm sunrise landscape background, soft overlay, headline, short business introduction, and call-to-action buttons.

- **Welcome**  
  Introductory section explaining the business’s role in helping families during difficult times.

- **About**  
  Company background, mission, values, and positioning as a fully woman-owned, BBBEE-compliant funeral business.

- **Funeral Services**  
  Detailed service cards covering body pickup, transportation, deceased preparation, embalming, coffins, floral arrangements, funeral coordination, burial, cremation, and grave bookings.

- **Packages**  
  Funeral package overview with budget-friendly, standard, and premium package options.

- **Funeral Insurance**  
  Bronze, Silver, and Gold funeral cover tables with monthly premium details.

- **FAQ**  
  Bootstrap accordion containing common funeral-related questions and answers.

- **Contact**  
  Contact details, enquiry form, and WhatsApp click-to-chat support.

- **Footer**  
  Quick links, company summary, and copyright information.

---

## Features

- Fully responsive one-page layout
- Mobile-friendly Bootstrap 5 navigation bar
- Tailwind CSS utility-based styling
- Calm funeral-appropriate visual design
- Hero background image with soft overlay
- Section-based smooth scrolling
- Active navigation link behavior
- WhatsApp floating “Click to Chat” button
- Contact enquiry form
- Gmail SMTP email sending using PHPMailer
- Bootstrap FAQ accordion
- SEO meta tags
- OpenGraph sharing tags
- LocalBusiness / FuneralHome schema markup
- Clean project folder structure for GitHub portfolio presentation

---

## Tech Stack

### Frontend

- HTML5
- CSS3
- Bootstrap 5
- Tailwind CSS
- JavaScript

### Backend / Email Handling

- PHP
- PHPMailer
- Composer
- Gmail SMTP

### Tools

- Git
- GitHub
- Git Bash
- VS Code
- npm
- Tailwind CLI

---

## Project Folder Structure

```bash
gcilishe-funerals/
├── node_modules/
├── public/
├── src/
│   ├── styles/
│   │   ├── styles.css
│   │   └── tailwind.css
│   ├── pages/
│   │   └── index.js
│   └── api/
│       └── send.php
├── index.html
├── package.json
├── package-lock.json
├── .gitignore
└── README.md
```

---

## Tailwind CSS Setup

Install Tailwind CSS and the Tailwind CLI:

```bash
npm init -y
npm install -D tailwindcss @tailwindcss/cli
```

Inside `src/styles/styles.css`, add:

```css
@import "tailwindcss";
```

Compile Tailwind CSS:

```bash
npx @tailwindcss/cli -i ./src/styles/styles.css -o ./src/styles/tailwind.css --watch
```

The compiled CSS file is linked inside `index.html`:

```html
<link rel="stylesheet" href="./src/styles/tailwind.css" />
```

---

## Bootstrap 5 Setup

Bootstrap 5 is included using the CDN.

Inside the `<head>`:

```html
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
```

Before the closing `</body>` tag:

```html
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
```

---

## WhatsApp Click-to-Chat Setup

The floating WhatsApp button uses the official WhatsApp click-to-chat format:

```js
https://wa.me/27XXXXXXXXX?text=Your%20message
```

Update the WhatsApp number inside:

```bash
src/pages/index.js
```

Example:

```js
const WHATSAPP_NUMBER_E164 = "27721234567";
```

Use the South African number format without the `+` symbol.

---

## Enquiry Form Email Setup

The enquiry form sends messages through:

```bash
src/api/send.php
```

PHPMailer is installed through Composer:

```bash
composer require phpmailer/phpmailer
```

The email function uses Gmail SMTP:

```php
$mail->Host = "smtp.gmail.com";
$mail->SMTPAuth = true;
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;
```

### Important Security Note

Do not commit real Gmail passwords or app passwords to GitHub.

Use placeholders in the public repository:

```php
$SMTP_USER = "yourgmail@gmail.com";
$SMTP_PASS = "your_gmail_app_password";
```

For production, use environment variables.

---

## GitHub Setup

Initialize Git:

```bash
git init
```

Create a `.gitignore` file:

```bash
touch .gitignore
```

Recommended `.gitignore` content:

```gitignore
node_modules/
.env
vendor/
.DS_Store
.vscode/
```

Add and commit the project:

```bash
git add .
git commit -m "Add responsive funeral service website"
```

Connect the local project to GitHub:

```bash
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPOSITORY_NAME.git
git push -u origin main
```

For future updates:

```bash
git add .
git commit -m "Update website"
git push
```

---

## How to Run the Project Locally

Open the project folder in VS Code:

```bash
code .
```

Run Tailwind in watch mode:

```bash
npx @tailwindcss/cli -i ./src/styles/styles.css -o ./src/styles/tailwind.css --watch
```

Open `index.html` in the browser or use the VS Code Live Server extension.

---

## Portfolio Purpose

This project demonstrates the ability to:

- Build a professional business landing page
- Structure a responsive one-page website
- Combine Bootstrap 5 and Tailwind CSS
- Create a mobile-friendly user interface
- Add JavaScript functionality
- Add SEO and OpenGraph metadata
- Integrate WhatsApp for lead generation
- Prepare a project for GitHub portfolio presentation
- Use basic backend email handling with PHPMailer

---

## Future Improvements

Possible improvements include:

- Add real business logo and brand identity
- Replace stock images with custom business images
- Add Google Maps location
- Add testimonials section
- Add online funeral package quote calculator
- Add admin dashboard for enquiries
- Add database storage for form submissions
- Deploy the site on a live hosting platform
- Improve security using environment variables
- Add Google Analytics or Meta Pixel tracking

---

## Author

Created by **Thuso Ngogodo** as part of a web development portfolio project.

---

## License

This project is for educational and portfolio purposes.
