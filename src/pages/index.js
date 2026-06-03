import { sendEnquiry } from "../api/contactapi.js";

function setYear() {
  const el = document.getElementById("year");
  if (el) el.textContent = new Date().getFullYear();
}

function buildWhatsAppLink({ phone, message }) {
  const text = encodeURIComponent(message || "");
  // phone format: international, no +, no spaces e.g. 27821234567
  return `https://wa.me/${phone}?text=${text}`;
}

function setWhatsAppLinks() {
  // ✅ Replace with your real WhatsApp number in international format
  const WHATSAPP_PHONE = "2XXXXXXXXXX"; // e.g. 27821234567 for South Africa

  const defaultMsg =
    "Hi Gcilishe Taliwe Classic Funerals. I’d like assistance with a funeral enquiry. Please contact me.";

  const waLink = buildWhatsAppLink({ phone: WHATSAPP_PHONE, message: defaultMsg });

  const floatBtn = document.getElementById("waFloat");
  const heroBtn = document.getElementById("heroWhatsAppLink");
  const textLink = document.getElementById("waText");
  const assistBtn = document.getElementById("whatsAppAssistBtn");

  [floatBtn, heroBtn, textLink, assistBtn].forEach((el) => {
    if (!el) return;
    el.href = waLink;
    el.target = "_blank";
    el.rel = "noopener";
  });
}

function enableScrollSpy() {
  const nav = document.getElementById("mainNav");
  if (!nav) return;

  // Bootstrap ScrollSpy refresh
  try {
    bootstrap.ScrollSpy.getOrCreateInstance(document.body, {
      target: "#mainNav",
      offset: 90,
    });
  } catch (_) {}
}

function wireForm() {
  const form = document.getElementById("enquiryForm");
  const status = document.getElementById("formStatus");
  const submitBtn = document.getElementById("submitBtn");

  if (!form) return;

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    form.classList.add("was-validated");
    if (!form.checkValidity()) return;

    const formData = new FormData(form);
    const payload = {
      fullName: formData.get("fullName")?.toString().trim(),
      contactNumber: formData.get("contactNumber")?.toString().trim(),
      email: formData.get("email")?.toString().trim(),
      enquiryType: formData.get("enquiryType")?.toString().trim(),
      message: formData.get("message")?.toString().trim(),
      page: window.location.href,
    };

    try {
      if (status) status.textContent = "Sending…";
      if (submitBtn) submitBtn.disabled = true;

      const result = await sendEnquiry(payload);

      if (status) status.textContent = result?.message || "Enquiry sent successfully.";
      form.reset();
      form.classList.remove("was-validated");
    } catch (err) {
      if (status) status.textContent = err?.message || "Failed to send enquiry.";
    } finally {
      if (submitBtn) submitBtn.disabled = false;
      setTimeout(() => {
        if (status) status.textContent = "";
      }, 6000);
    }
  });
}

setYear();
setWhatsAppLinks();
enableScrollSpy();
wireForm();
