
let stat = ''

function radio(id) {
    $("#admin").removeClass("admin-selected");
    $("#teacher").removeClass("teacher-selected");
    $("#student").removeClass("student-selected");
    switch (id) {
        case 'admin':
            $("#admin").addClass("admin-selected");
            stat = 'admin'
            break;
        case 'teacher':
            $("#teacher").addClass("teacher-selected");
            stat = 'teacher'
            break;
        case 'student':
            $("#student").addClass("student-selected");
            stat = 'student'
            break;
    }
    // Swal.fire({
    //     icon: 'info',
    //     title: 'You select ' + id,
    //     text: '',
    // });
}

function LoginUser() {
    if(stat == ''){
        Swal.fire('Error !!','กรุณาเลือกสถานะ','error')
        return  
    } 
    var username = $("input[name=username]").val();
    var password = $("input[name=password]").val();
    var token = $("input[name=_token]").val();
    console.log("Username : ", username);
    console.log("Password : ", password);
    console.log("Token : ", token);

    var data = {
        _token: token,
        username: username,
        password: password,
        stat: stat
    };

    console.log(data);
    

    // Ajax Post 
    $.ajax({
        type: "post",
        url: "/login/user",
        data: data,
        cache: false,
        success: function (data) {
            console.log('login request sent !');
            console.log('response data : ',data);
            
                Swal.fire({
                    icon: 'success',
                    title: 'Welcome back ',
                    html: data.name + ' <i style="color:ping;" class="fas fa-xs fa-heart"></i>',
                }).then(() => {
                    window.location.href = '/home';
                });


        },
        error: function (data) {
            Swal.fire('Error !!',data.responseJSON.message,'error');
            console.log(data);
        }
    });

    return false;
}