<h3 style="margin:0">Comments</h3>
<ul id="comment-container">
    <li>
        <div class="cmt">
            <div class="cmt-info">
                <span class="cmt-name">원산폭격의임종만</span><span class="cmt-date">2020-04-26</span>
            </div>
            <div class="cmt-content">
                반갑습니다.
            </div>
        </div>
    </li>
    <li>
        <div class="cmt">
            <div class="cmt-info">
                <span class="cmt-name">원산폭격의임종만</span><span class="cmt-date">2020-04-26</span>
            </div>
            <div class="cmt-content">
                반갑습니다.
            </div>
        </div>
    </li>
    <li>
        <div class="cmt">
            <div class="cmt-info">
                <span class="cmt-name">원산폭격의임종만</span><span class="cmt-date">2020-04-26</span>
            </div>
            <div class="cmt-content">
                반갑습니다.
            </div>
        </div>
    </li>
</ul>
<form class="comment-write-box" method="post" action="_comment_write_process.php">
    <div class="comment-write-head">
        <?
            if($nickname = get_login_nickname())
                echo '<div class="cmt-name long">'.$nickname.'</div>';
            else{
                echo '
                <input type="text" name="cmt-write-name" id="cmt-write-name" placeholder="닉네임" value="">
                <input type="password" name="cmt-write-pw" id="cmt-write-pw" placeholder="패스워드" value="">
                ';
            }
        ?>
    </div>
    <div class="comment-write-textarea">
        <textarea name="cmt-write-content" rows="3" cols="80"></textarea>
    </div>
    <div class="comment-write-btn">
        <input type="button" name="" value="등록">
    </div>
</form>


<script>

    
</script>
