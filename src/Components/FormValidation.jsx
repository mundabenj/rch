export function validateFullname(value) {
  if (!value?.trim()) {
    return "Full name is required";
  }

  if (value.trim().length < 3) {
    return "Full name must be at least 3 characters";
  }

  var fullnameRegex = /^[A-Za-z]+(?:[\s'-][A-Za-z]+)*$/;
  if (!fullnameRegex.test(value.trim())) {
    return "Please enter a valid full name (letters, spaces, apostrophes, hyphens)";
  }

  return true;
}

export function validateEmail(value) {
  if (!value?.trim()) {
    return "Email is required";
  }
  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
  if (!emailRegex.test(value.trim())) {
    return "Please enter a valid email address";
  }

  var validDomains = ["gmail.com", "yahoo.com", "outlook.com"];
  var domain = value.trim().split("@")[1];
  if (!validDomains.includes(domain)) {
    return "Email domain must be one of: gmail.com, yahoo.com, outlook.com";
  }  

  return true;
}