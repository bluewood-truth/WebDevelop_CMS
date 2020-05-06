// form id, post 이름, 값
function set_post(parentId, name, value){
    var html = "<input name='"+name+"' value='"+value+"' class='hidden-data'>";
    $("#"+parentId).append(html);
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

// 중복체크할 값, 중복체크할 테이블과 열
function login_check(id, pw){
    var result;
    $.ajax({
        url:"http://uraman.m-hosting.kr/ex_cms/common/process/_login_check.php",
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
    // 중복이면 true, 중복아니면 false
    return result;
}


function goto_board(board_id){
    location.href="http://uraman.m-hosting.kr/ex_cms/board/?id="+board_id;
}

// 로그아웃
function logout(){
    location.href="http://uraman.m-hosting.kr/ex_cms/common/process/_logout_process.php";
}
