    

    function remove_cat(tag){
        var cat = tag.closest("div#category_list>div");
        cat.remove();
    }

    function edit_cat(tag){
        var cat = tag.closest("div#category_list>div");
        edit_cancel();
        shift_edit_display(cat,"edit");

        var cat_input = cat.querySelector("div.edit input");
        cat_input.focus();
        var tmp = cat_input.value;
        cat_input.value = "";
        cat_input.value = tmp;
    }

    function edit_cancel(){
        var cat_list = $("#category_list > div").toArray();
        for(i = 0; i < cat_list.length; i++){
            shift_edit_display(cat_list[i],"display");
        }
    }

    function shift_edit_display(cat,on){
        var off = "";
        if(on == "display")
            off="edit";
        if(on == "edit")
            off="display";

        if(on=="display"){
            cat.querySelector("input.editname").value = cat.querySelector("span.name").innerText;
        }

        if(cat.querySelector("div."+on+".invisible") != null)
            cat.querySelector("div."+on+".invisible").classList.remove("invisible");
        if(cat.querySelector("div."+off) != null)
            cat.querySelector("div."+off).classList.add("invisible");
    }

    function edit_done(tag){
        var cat = tag.closest("div#category_list>div");
        var new_cat_name = cat.querySelector("input.editname").value;
        if(new_cat_name.length > 7 || new_cat_name.length < 1){
            alert("글자수는 1~7자여야 합니다.");
            return;
        }

        if(cat_duplicate_check(new_cat_name, cat)){
            alert("이미 존재하는 분류명입니다.");
            return;
        }

        cat.querySelector("span.name").innerText = new_cat_name;
        cat.querySelector("input.hdn").value = new_cat_name;
        shift_edit_display(cat,"display");
    }

    function add_cat(){
        var new_cat = $("#category input.add_value")[0].value;
        if(new_cat.length > 7 || new_cat.length < 1){
            alert("글자수는 1~7자여야 합니다.");
            return;
        }

        if(cat_duplicate_check(new_cat)){
            alert("이미 존재하는 분류명입니다.");
            return;
        }

        var new_element = $('<div>\
            <div class="edit invisible">\
                <input type="text" class="editname" onkeydown="if(event.keyCode == 13)edit_done(this)" minlength="1" maxlength="7" value="'+new_cat+'">\
                <div class="btns">\
                    <input type="button" onclick="edit_done(this)" value="확인">\
                </div>\
            </div>\
            <div class="display">\
                <span class="name">'+new_cat+'</span>\
                <div class="btns">\
                    <a onclick="edit_cat(this)"><img src="/ex_cms/images/icon_edit.png"></a>\
                    <a onclick="remove_cat(this)"><img src="/ex_cms/images/icon_cancel.png"></a>\
                </div>\
                <input type="hidden" class="hdn" name="cat[]" value="'+new_cat+'">\
            </div>\
        </div>')[0];
        var cat_list = $("#category_list")[0];
        cat_list.appendChild(new_element);
        $("#category input.add_value")[0].value = "";
    }

    function cat_duplicate_check(new_cat, this_cat = null){
        var cat_list = $("#category_list > div").toArray();
        for(i = 0; i < cat_list.length; i++){
            if(this_cat != null){
                if(this_cat == cat_list[i]){
                    continue;
                }
            }
            if(cat_list[i].querySelector("span.name").innerText == new_cat)
                return true;
        }
        return false;
    }

    // 엔터쳤을때 submit 방지
    $(document).on("keypress", 'form', function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            e.preventDefault();
            return false;
        }
    });
