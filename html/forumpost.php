<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MTG | Forum</title>

    <link rel="icon" type="image/x-icon" href="/img/favicon.ico">
	<link rel="stylesheet" type="text/css" href="/css/style.css">
    <link rel="stylesheet" type="text/css" href="/css/forum.css">
</head>

<body>
    <?php include_once "header.php";?>

    <div class="box box-row">
        <h class="post-title"> 
            Hallo dit is de test titel.
        </h>
        <p class="post-timestamp">
            1-1-1984
        </p>
        <p class="post-content">
            Wat da fuq is een magic?
        </p>

        <textarea></textarea>
        <button>Add comment</button>

        <div class="comments">
            <div class="comment">
                <div class="comment-header">
                    <p class="user">
                        User
                    </p>   
                    <p class="comment-timestamp">
                        1-1-2001    
                    </p>
                </div>
                <p class="comment-content">
                    First comment.
                </p>
            </div>
            <div class="comment">
                <div class="comment-header">
                    <p class="user">
                        User
                    </p>   
                    <p class="comment-timestamp">
                        1-1-2001    
                    </p>
                </div>
                <p class="comment-content">
                    First comment.
                </p>
            </div>
        </div>

    </div>

    <?php include_once "footer.php"; ?>
</body>

</html>
