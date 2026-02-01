// Open Add Member Form
function openAddForm() {
    document.getElementById('formTitle').textContent = 'Add New Member';
    document.getElementById('formSubtitle').textContent = 'Create a new member account';
    document.getElementById('formAction').value = 'add';
    document.getElementById('userId').value = '';
    document.getElementById('memberForm').reset();
    document.getElementById('passwordGroup').style.display = 'block';
    document.getElementById('password').required = true;
    
    showFormPopup();
}

// Open Edit Member Form
function openEditForm(member) {
    document.getElementById('formTitle').textContent = 'Edit Member';
    document.getElementById('formSubtitle').textContent = 'Update member information';
    document.getElementById('formAction').value = 'edit';
    document.getElementById('formMethod').value = 'edit';
    document.getElementById('userId').value = member.user_id;
    document.getElementById('fullName').value = member.full_name;
    document.getElementById('email').value = member.email;
    document.getElementById('username').value = member.username;
    document.getElementById('passwordGroup').style.display = 'none';
    document.getElementById('password').required = false;
    
    showFormPopup();
}

// Show Form Popup
function showFormPopup() {
    document.getElementById('memberFormPopup').style.display = 'block';
    document.getElementById('formOverlay').style.display = 'block';
}

// Close Form Popup
function closeForm() {
    document.getElementById('memberFormPopup').style.display = 'none';
    document.getElementById('formOverlay').style.display = 'none';
}

// Form Submit Handler
document.getElementById('memberForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const action = document.getElementById('formMethod').value;
    const baseUrl = window.BASE_URL || '/';
    // Đảm bảo baseUrl kết thúc bằng /
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
            user_id: document.getElementById('userId').value
        }
    });
    
    this.action = url;
    console.log('Form action set to:', this.action);
    this.submit();
});
