// Store original members data for search
let allMembers = [];

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Extract members data from table rows
    const rows = document.querySelectorAll('#membersTableBody tr');
    allMembers = Array.from(rows).map(row => {
        const cells = row.querySelectorAll('td');
        return {
            user_id: cells[0].textContent,
            full_name: cells[1].textContent,
            email: cells[2].textContent,
            username: cells[3].textContent,
            status: cells[4].textContent.trim()
        };
    });
    
    // Add search event listener
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            searchMembers(this.value);
        });
    }
});

// Search members by name, email, or username
function searchMembers(keyword) {
    const keyword_lower = keyword.toLowerCase();
    const rows = document.querySelectorAll('#membersTableBody tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const fullName = row.cells[1].textContent.toLowerCase();
        const email = row.cells[2].textContent.toLowerCase();
        const username = row.cells[3].textContent.toLowerCase();
        
        // Check if any field matches keyword
        const matches = fullName.includes(keyword_lower) || 
                       email.includes(keyword_lower) || 
                       username.includes(keyword_lower);
        
        if (matches) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Show "no results" message if needed
    if (visibleCount === 0) {
        console.log('No members found matching: ' + keyword);
    }
}

function openAddForm() {
    document.getElementById('formTitle').textContent = 'Add New Member';
    document.getElementById('formSubtitle').textContent = 'Create a new member account';
    document.getElementById('formAction').value = 'add';
    document.getElementById('userId').value = '';
    document.getElementById('memberForm').reset();
    document.getElementById('passwordGroup').style.display = 'block';
    document.getElementById('password').required = true;
    document.getElementById('username').readOnly = false;
    
    showFormPopup();
}

function openEditForm(member) {
    document.getElementById('formTitle').textContent = 'Edit Member';
    document.getElementById('formSubtitle').textContent = 'Update member information';
    document.getElementById('formAction').value = 'edit';
    document.getElementById('formMethod').value = 'edit';
    document.getElementById('userId').value = member.user_id;
    document.getElementById('fullName').value = member.full_name;
    document.getElementById('email').value = member.email;
    document.getElementById('username').value = member.username;
    document.getElementById('username').readOnly = false; // Allow editing username
    document.getElementById('passwordGroup').style.display = 'none';
    document.getElementById('password').required = false;
    
    showFormPopup();
}

function showFormPopup() {
    document.getElementById('memberFormPopup').style.display = 'block';
    document.getElementById('formOverlay').style.display = 'block';
}

function closeForm() {
    document.getElementById('memberFormPopup').style.display = 'none';
    document.getElementById('formOverlay').style.display = 'none';
}

document.getElementById('memberForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const action = document.getElementById('formMethod').value;
    const baseUrl = window.BASE_URL || '/';
    const base = baseUrl.endsWith('/') ? baseUrl : baseUrl + '/';
    const url = action === 'add' 
        ? base + 'admin/member/add'
        : base + 'admin/member/edit';
    
    console.log('Form submitted:', {
        action: action,
        baseUrl: baseUrl,
        finalUrl: url,
        formData: {
            fullname: document.getElementById('fullName').value,
            email: document.getElementById('email').value,
            username: document.getElementById('username').value,
            user_id: document.getElementById('userId').value
        }
    });
    
    this.action = url;
    console.log('Form action set to:', this.action);
    this.submit();
});
