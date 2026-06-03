export async function sendEnquiry(payload) {
  const res = await fetch("../../public/api/send-email.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(payload),
  });

  const data = await res.json().catch(() => ({}));

  if (!res.ok) {
    const msg = data?.error || "Failed to send enquiry. Please try again.";
    console.log(msg)
    throw new Error(msg);
  }

  return data;
}
