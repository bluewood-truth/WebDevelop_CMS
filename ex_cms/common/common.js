// form id, post 이름, 값
function set_post(parentId, name, value){
    var html = "<input name='"+name+"' value='"+value+"' type='hidden'>";
    $("#"+parentId).append(html);
}

function hello(){
    console.log("hello world!");
}

// 중복체크할 값, 중복체크할 테이블과 열
function duplicate_check(value,table,col,only_not_deleted = false){
    var result;
    $.ajax({
        url:"/ex_cms/common/process/_duplicate_check.php",
        method:"POST",
        async:false,
        data: {"value":value, "table":table, "col":col, "only_not_deleted":only_not_deleted},
        dataType: "text",
        success:function(data){
            result = data=="true" ? true : false;
        }}
    );
    // 중복이면 true, 중복아니면 false
    return result;
}

// 로그인 시 아이디와 패스워드가 일치하는 계정이 있는지 체크
function check_user_id_pw_valid(id, pw){
    var result;
    $.ajax({
        url:"http://uraman.m-hosting.kr/ex_cms/common/process/_check_user_id_pw_valid.php",
        method:"POST",
        async:false,
        data: {"id":id, "pw":pw},
        dataType: "text",
        success:function(data){
            result = data=="true" ? true : false;

            console.log(data);
            console.log(result);
        }}
    );
    // 존재하는 계정이면 true, 아니면 false
    return result;
}


function goto_board(board_id){
    location.href="http://uraman.m-hosting.kr/ex_cms/board/?id="+board_id;
}

// 로그아웃
function logout(){
    location.href="http://uraman.m-hosting.kr/ex_cms/common/process/_logout_process.php";
}

// 로그인 되어있는지 체크
function is_logined(){
    var result;
    $.ajax({
        url:"http://uraman.m-hosting.kr/ex_cms/common/process/_is_login_check.php",
        async:false,
        dataType: "text",
        success:function(data){
            result = data=="true" ? true : false;

            console.log(data);
            console.log(result);
        }}
    );
    return result;
}

// 관리자인지 체크
function is_admin(is_super = false){
    var result = false;
    $.ajax({
        url:"http://uraman.m-hosting.kr/ex_cms/common/process/_is_admin_check.php",
        async:false,
        dataType: "text",
        success:function(data){
            if(data == "super_admin")
                result = true;
            if(is_super == false && data == "admin")
                result = true;

            console.log(data);
            console.log(result);
        }}
    );
    return result;
}

// 제목 등이 너무 길때 자르는 기능
function text_cutting(title, maxlength){
    if(title.length > maxlength){
        title = title.substr(0,maxlength) + "...";
    }
    return title;
}
