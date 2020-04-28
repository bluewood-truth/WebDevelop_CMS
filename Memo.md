### 루프문으로 addEventLister  + 인자 전달하기

```Javascript
for(var i = 0; i < buttons.length; i++){
    // 루프문의 내용물을 (function(){내용물})() 으로 묶는다
    // 또 addEventListener에 넣는 함수도 익명함수로 만든다
    (function(){
        var button = buttons[i];
        button.addEventListener("click",function(){
            clickEvent(button.id);
        });
    }());
}

function clickEvent(id){
    alert(id);
}
```

