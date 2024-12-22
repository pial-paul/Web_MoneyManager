// Validation for Sign In form
document
  .querySelector(".sign-in-form")
  .addEventListener("submit", function (e) {
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value;

    if (!username) {
      alert("Username or email is required.");
      e.preventDefault();
    }

    if (!password) {
      alert("Password is required.");
      e.preventDefault();
    }
  });

// Validation for Sign Up form
document.querySelector(".signup-form").addEventListener("submit", function (e) {
  const firstName = document.getElementById("first-name").value.trim();
  const lastName = document.getElementById("last-name").value.trim();
  const username = document.getElementById("username").value.trim();
  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("password").value;

  if (!firstName) {
    alert("First Name is required.");
    e.preventDefault();
  }

  if (!lastName) {
    alert("Last Name is required.");
    e.preventDefault();
  }

  if (!username) {
    alert("Username is required.");
    e.preventDefault();
  }

  if (!email || !/\S+@\S+\.\S+/.test(email)) {
    alert("A valid email is required.");
    e.preventDefault();
  }

  if (!password || password.length < 8) {
    alert("Password must be at least 8 characters.");
    e.preventDefault();
  }
});

// Validation for Transaction Form
document
  .querySelector(".transaction-form")
  .addEventListener("submit", function (e) {
    const type = document.getElementById("type").value;
    const date = document.getElementById("date").value;
    const amount = document.getElementById("amount").value.trim();
    const category = document.getElementById("category").value.trim();

    if (!type) {
      alert("Please select a type (Income/Expense).");
      e.preventDefault();
    }

    if (!date) {
      alert("Date is required.");
      e.preventDefault();
    }

    if (!amount || isNaN(amount) || amount <= 0) {
      alert("A valid amount is required.");
      e.preventDefault();
    }

    if (!category) {
      alert("Category is required.");
      e.preventDefault();
    }
  });

//Settings JS
document
  .querySelector(".settings-form")
  .addEventListener("submit", function (e) {
    e.preventDefault(); // Prevent form submission

    const firstName = document.getElementById("first-name").value.trim();
    const lastName = document.getElementById("last-name").value.trim();
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value;

    if (!firstName || !lastName || !username || !password) {
      alert("All fields are required.");
      return;
    }

    if (password.length < 8) {
      alert("Password must be at least 8 characters.");
      return;
    }

    alert("Changes saved successfully!");
  });
