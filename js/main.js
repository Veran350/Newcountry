/* js/main.js */

// Firebase configuration (replace the placeholder values with your actual config)
const firebaseConfig = {
  apiKey: "YOUR_API_KEY",
  authDomain: "YOUR_PROJECT_ID.firebaseapp.com",
  projectId: "YOUR_PROJECT_ID",
  storageBucket: "YOUR_PROJECT_ID.appspot.com",
  messagingSenderId: "YOUR_SENDER_ID",
  appId: "YOUR_APP_ID"
};

// Initialize Firebase (only once)
if (!firebase.apps.length) {
  firebase.initializeApp(firebaseConfig);
}
const db = firebase.firestore();
const storage = firebase.storage();

/* ---------- Load Projects and Display on Homepage ---------- */
function loadProjects() {
  const container = document.getElementById("projectsContainer");
  db.collection("projects")
    .orderBy("createdAt", "desc")
    .onSnapshot((snapshot) => {
      container.innerHTML = ""; // Clear current content
      snapshot.forEach((doc) => {
        const project = doc.data();
        const projectId = doc.id;
        const card = document.createElement("div");
        card.className = "project-card";

        // Check if file is an image or a video (case-insensitive check)
        if (project.fileUrl.match(/\.(jpeg|jpg|gif|png)$/i)) {
          card.innerHTML = `
            <img src="${project.fileUrl}" alt="${project.description}" />
            <h3>${project.description}</h3>
            <p>Price: ${project.price}</p>
          `;
        } else {
          card.innerHTML = `
            <video controls src="${project.fileUrl}"></video>
            <h3>${project.description}</h3>
            <p>Price: ${project.price}</p>
          `;
        }

        // Add Edit and Delete buttons only if admin is logged in
        if (localStorage.getItem("isAdmin") === "true") {
          const btnContainer = document.createElement("div");
          btnContainer.className = "admin-btns";

          // Edit button
          const editBtn = document.createElement("button");
          editBtn.textContent = "Edit";
          editBtn.onclick = () => editProject(projectId, project.description, project.price);
          btnContainer.appendChild(editBtn);

          // Delete button
          const deleteBtn = document.createElement("button");
          deleteBtn.textContent = "Delete";
          deleteBtn.onclick = () => deleteProject(projectId);
          btnContainer.appendChild(deleteBtn);

          card.appendChild(btnContainer);
        }

        container.appendChild(card);
      });
    });
}

/* ---------- Upload New Project ---------- */
function uploadProject() {
  const fileInput = document.getElementById("fileInput");
  const descriptionInput = document.getElementById("descriptionInput");
  const priceInput = document.getElementById("priceInput");
  const file = fileInput.files[0];

  if (!file) {
    alert("Please select a file.");
    return;
  }

  // Create a reference in Firebase Storage and upload the file
  const storageRef = storage.ref(`projects/${file.name}`);
  const uploadTask = storageRef.put(file);

  uploadTask.on(
    "state_changed",
    (snapshot) => {
      // Optionally track upload progress here
    },
    (error) => {
      console.error("Upload error:", error);
      alert("Upload failed!");
    },
    async () => {
      // Once upload is complete, get the download URL and add project info to Firestore
      const fileUrl = await storageRef.getDownloadURL();
      db.collection("projects")
        .add({
          fileUrl,
          description: descriptionInput.value,
          price: priceInput.value,
          createdAt: firebase.firestore.FieldValue.serverTimestamp(),
        })
        .then(() => {
          alert("Project uploaded successfully!");
          // Clear form fields
          fileInput.value = "";
          descriptionInput.value = "";
          priceInput.value = "";
        })
        .catch((error) => {
          console.error("Error adding project:", error);
        });
    }
  );
}

/* ---------- Edit a Project ---------- */
function editProject(projectId, currentDescription, currentPrice) {
  const newDescription = prompt("Enter new description:", currentDescription);
  if (newDescription === null) return; // Cancelled

  const newPrice = prompt("Enter new price:", currentPrice);
  if (newPrice === null) return; // Cancelled

  db.collection("projects").doc(projectId)
    .update({
      description: newDescription,
      price: newPrice,
    })
    .then(() => {
      alert("Project updated successfully!");
    })
    .catch((error) => {
      console.error("Error updating project:", error);
      alert("Error updating project");
    });
}

/* ---------- Delete a Project ---------- */
function deleteProject(projectId) {
  if (confirm("Are you sure you want to delete this project?")) {
    db.collection("projects").doc(projectId)
      .delete()
      .then(() => {
        alert("Project deleted successfully!");
      })
      .catch((error) => {
        console.error("Error deleting project:", error);
        alert("Error deleting project");
      });
  }
}

/* ---------- Admin Password Check ---------- */
function checkPassword() {
  const pwd = document.getElementById("adminPassword").value;
  // Simple client-side password check – change "admin123" to your chosen password
  if (pwd === "admin123") {
    localStorage.setItem("isAdmin", "true");
    document.getElementById("passwordSection").style.display = "none";
    document.getElementById("uploadForm").style.display = "block";
  } else {
    alert("Incorrect password!");
  }
}

/* ---------- Admin Logout Function ---------- */
function logoutAdmin() {
  localStorage.removeItem("isAdmin");
  alert("You have been logged out.");
  // Redirect to the homepage after logout
  window.location.href = "index.html";
}
