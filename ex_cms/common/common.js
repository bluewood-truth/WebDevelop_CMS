function set_post(parentId, name, value){
    var html = "<input name='"+name+"' value='"+value+"' style='visibility:hidden'>";
    $("#"+parentId).append(html);
}
