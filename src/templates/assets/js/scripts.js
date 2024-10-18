function showToast(message, type) {
  const toast = document.createElement("div");
  toast.className = `toast ${type}`; // 'success' or 'error'
  toast.innerText = message;

  document.body.appendChild(toast);

  setTimeout(() => {
    toast.remove();
  }, 5000); // Toast will disappear after 2 seconds
}
function toggleDropdown(event) {
  event.stopPropagation(); // Prevent event bubbling
  const dropdownContainer = event.target.closest(".dropdown");
  console.log(dropdownContainer);
  const dropdown = dropdownContainer.querySelector(".dropdown-content");
  console.log(dropdown);
  if (dropdown) {
    dropdown.style.display =
      dropdown.style.display === "block" ? "none" : "block";
  }
}

document.addEventListener("click", function (event) {
  // Hide dropdown when clicking outside
  let dropdowns = document.querySelectorAll(".dropdown-content");
  dropdowns.forEach((dropdown) => (dropdown.style.display = "none"));
});
