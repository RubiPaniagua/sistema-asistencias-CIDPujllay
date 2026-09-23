    //*
   todo esto eh hecho para probar como funciona los dos lados el navegador clasico vs con json es puramente probatorio 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Lista de Carreras</h1>
    <button onclick="traerCarreras()">Traer carreras con javascript</button>
    
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($carreras as $carrera)
            <tr>
                <td>{{ $carrera->id }}</td>
                <td>{{ $carrera->nombre }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
        </tr>
    </thead>
    <tbody  id="navegador"></tbody>
</table>

<script>

    //*
    todo esto eh hecho para probar como funciona los dos lados el navegador clasico vs con json es puramente probatorio 
    function traerCarreras() {
        fetch('/carreras', {
            headers: {
                'Accept': 'application/json'
            }})
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('navegador');
                tbody.innerHTML = ''; // Limpiar el contenido actual de la tabla

                data.forEach(carrera => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${carrera.id}</td>
                        <td>${carrera.nombre}</td>
                    `;
                    tbody.appendChild(row);
                });
            })
            .catch(error => console.error('Error al traer las carreras:', error));
    }
</script>
</body>
</html>