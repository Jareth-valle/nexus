<?php
session_start(); // Iniciar sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$nombreUsuario = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comentarios</title>
    <style>
        /* Aquí puedes añadir estilos según tus necesidades */
    </style>
</head>
<body>
    <div class="comment-section">
        <h2>Deja tu Comentario</h2>
        <form class="comment-form" id="commentForm">
            <input type="text" id="commentName" value="<?php echo htmlspecialchars($nombreUsuario); ?>" readonly>
            <textarea id="commentText" placeholder="Escribe tu comentario aquí..." required></textarea>
            <button type="submit">Enviar Comentario</button>
        </form>
        <ul class="comments" id="commentList">
            <!-- Los comentarios se agregarán aquí -->
        </ul>
    </div>

    <script>
        document.getElementById('commentForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Evita el envío del formulario

            // Obtén el valor del nombre y del comentario
            var commentName = document.getElementById('commentName').value;
            var commentText = document.getElementById('commentText').value;

            // Envía el comentario al servidor
            fetch('comments.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    'name': commentName,
                    'comment': commentText
                })
            })
            .then(response => response.text())
            .then(data => {
                console.log(data); // Opcional: muestra la respuesta en la consola
                loadComments(); // Recarga los comentarios
            });

            // Limpia el campo de texto
            document.getElementById('commentText').value = '';
        });

        function loadComments() {
            fetch('comments.php')
                .then(response => response.json())
                .then(data => {
                    var commentList = document.getElementById('commentList');
                    commentList.innerHTML = ''; // Limpia la lista de comentarios

                    data.forEach(comment => {
                        var li = document.createElement('li');
                        var formattedDate = formatDate(comment.created_at);
                        li.innerHTML = `<strong>${comment.name}</strong>: ${comment.comment} <br><small>${formattedDate}</small>`;
                        commentList.appendChild(li);
                    });
                });
        }

        function formatDate(dateString) {
            var date = new Date(dateString);

            var hours = date.getHours();
            var minutes = date.getMinutes();
            var ampm = hours >= 12 ? 'PM' : 'AM';

            hours = hours % 12;
            hours = hours ? hours : 12; // La hora '0' debería ser '12'
            minutes = minutes < 10 ? '0' + minutes : minutes;

            var formattedTime = hours + ':' + minutes + ' ' + ampm;

            var formattedDate = date.toLocaleDateString('es-ES', { day: 'numeric', month: 'numeric', year: 'numeric' });

            return `${formattedDate} ${formattedTime}`;
        }

        window.onload = loadComments;
    </script>
</body>
</html>

?>
