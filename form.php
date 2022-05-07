<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>app</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/themes/smoothness/jquery-ui.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js"></script>
    <script>
    $(function() {
        const list = new Array(
            'AWS',
            'Bootstrap',
            'C',
            'CakePHP',
            'C#',
            'C++',
            'COBOL',
            'CSS',
            'Docker',
            'Go',
            'Git',
            'HTTP',
            'iOS',
            'Java',
            'JavaScript',
            'JIRA',
            'Kotlin',
            'Laravel',
            'MATLAB',
            'MySQL',
            'Oracle Database',
            'Perl',
            'PHP',
            'PostgreSQL',
            'Python',
            'R',
            'React',
            'Ruby',
            'Ruby on Rails',
            'Rust',
            'SVN',
            'SSL',
            'SQLite',
            'TypeScript',
            'Vue.js'
        );
        $("#input-message").autocomplete({
            source: "./autocomplete.php"
        });

        var parentDiv = document.getElementById("values"),
            p_values = document.getElementById("values");

        $("input[name=name]").on("keyup", function() {
            var fome_x_name = $(this).val();
            if (list.indexOf(fome_x_name) != -1) {
                //-------------------------
                // 追加する要素を作成します
                // ----------------------------
                var newElement = document.createElement("span"); // p要素作成
                var newContent = document.createTextNode(fome_x_name); // テキストノードを作成
                newElement.appendChild(newContent); // p要素にテキストノードを追加
                newElement.setAttribute("id", "child-p1"); // p要素にidを設定

                // ----------------------------
                // 親要素の最初の子要素を追加します
                // ----------------------------
                // 親要素（div）への参照を取得
                var parentDiv = document.getElementById("parent-div");

                // 追加
                parentDiv.insertBefore(newElement, parentDiv.firstChild);
            }
        });
    });
    </script>
</head>

<body>
    <form>
        <input name="name" id="input-message" />
        <div id="parent-div"></div>
    </form>
</body>

</html>