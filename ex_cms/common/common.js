// form id, post 이름, 값
function set_post(parentId, name, value){
    var html = "<input name='"+name+"' value='"+value+"' style='visibility:hidden'>";
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
            console.log(data);
        }}
    );
    // 중복이면 true, 중복아니면 false
    return result;
}
