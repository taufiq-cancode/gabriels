// Register User
async function registerUser() {

    document.getElementById('registerForm').addEventListener('submit', async (e) => {
        e.preventDefault(); // Prevent form submission and page reload
        await registerUser(); // Call registerUser function
    });
    
    const firstname = document.getElementById('registerFirstName').value;
    const lastname = document.getElementById('registerLastName').value;
    const email = document.getElementById('registerEmail').value;
    const password = document.getElementById('registerPassword').value;

    // Client-side validation
    if (!firstname || !lastname || !email || !password) {
        document.getElementById('registerError').textContent = "All fields are required.";
        return;
    }

    const data = { firstname, lastname, email, password };

    try {
        const response = await fetch('http://localhost:8000/api/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        const result = await response.json();
        
        if (response.ok) {
            alert('Registration successful! Please log in.');
        } else {
            document.getElementById('registerError').textContent = result.error || 'Registration failed.';
        }
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('registerError').textContent = 'An error occurred. Please try again.';
    }
}

// Login User
async function loginUser() {
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;

    // Client-side validation
    if (!email || !password) {
        document.getElementById('loginError').textContent = "All fields are required.";
        return;
    }

    const data = { email, password };

    try {
        const response = await fetch('http://localhost:8000/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        const result = await response.json();

        if (response.ok) {
            alert('Login successful!');
            // Redirect or store token as needed
        } else {
            document.getElementById('loginError').textContent = result.error || 'Login failed.';
        }
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('loginError').textContent = 'An error occurred. Please try again.';
    }
}
