<?php
// $servername = "sql208.infinityfr";
// $username = "if0_38591381";
// $password = "OQWrXAhqwiE";
// $dbname = "if0_38591381_gestorgastos ";



// // Crear conexión
// $conn = new mysqli($servername, $username, $password, $dbname);

// // Verificar la conexión
// if ($conn->connect_error) {
//     die("Conexión fallida: " . $conn->connect_error);
// } 

// //Verificar que se ha recibido el nombre de la tabla
// if(isset($_GET['nomUsu'])&&!empty($_GET['nomUsu'])){
//     $n=$_GET['nomUsu'];
//     //Açò fa que es substituisquen els caràcters no vàlids en SQL 
//     $nSegur = $conn->real_escape_string($nombreTabla);
//     $consulta = "SELECT * FROM ".$nSegur;
//     $resultat = $conn-> query($sql);

//     //la resposta la passem a un array php per a poder-lo passar, alhora a un json
//     $data = array();
//     if($result ->num_row > 0) {
//         while($row = $result -> fetch_assoc()){
//             $data[] = $row;
//         }
//     }

//     header('Content-Type: application/json');
//     echo json_encode($data);

// }else {
//     // Si no se proporciona el parámetro 'tabla', puedes enviar un error
//     http_response_code(400); // Bad Request
//     echo json_encode(array("error" => "Por favor, especifica el nombre de la tabla."));
// }

// $conn->close();


// Dona un nom al workflow (pots canviar-lo si vols).
name: Desplegament 
// Aquesta part diu: "Executa aquest workflow quan es fa un push a la branca master".
on:
  push:
    branches:
      - 'gh-pages'
// Defineix una feina (job) anomenada publish. La línia contents: write permet escriure al repositori si fos necessari (en aquest cas no és crucial).
jobs:
  publish:
    permissions:
      contents: write
// El workflow s'executarà en una màquina virtual amb Ubuntu.
    runs-on: ubuntu-latest
// Això clona el repositori perquè es pugui treballar amb els fitxers locals.
    steps:
      - name: Check out
        uses: actions/checkout@v4
// Aquesta acció sincronitza fitxers via FTP o SFTP.
      - name: Sync files
        uses: SamKirkland/FTP-Deploy-Action@v4.3.5
// Això indica:
// on trobar els fitxers locals a pujar (./build/)
// on s’han de copiar al servidor (/public_html/materiales/)
// les credencials d’accés (guardades com a secrets)
        with:
          server: ${{ secrets.SFTP_HOST }}
          username: ${{ secrets.SFTP_USER }}
          password: ${{ secrets.SFTP_PASS }}
          local-dir: ./build/
          server-dir: ./public_html/

/* 2. Crear els secrets
A GitHub, ves al teu repositori i fes:
Ves a Settings > Secrets and variables > Actions
Afegeix tres secrets amb aquests noms i valors:
SFTP_HOST: l’adreça del teu servidor (ex: ftp.elmeuservidor.com)
SFTP_USER: el teu usuari FTP/SFTP
SFTP_PASS: la contrasenya 

📁 3. Canviar les rutes si cal
Aquesta línia:

yaml
Copiar
Editar
local-dir: ./build/
Indica que els fitxers a pujar estan dins la carpeta build/. Si tens els fitxers a un altre lloc, canvia-ho (ex: ./dist/ o ./public/).

També pots canviar la destinació del servidor:

yaml
Copiar
Editar
server-dir: /public_html/materiales/
Modifica-ho segons la carpeta del teu allotjament web.

📤 4. Guardar el workflow
Crea un fitxer dins .github/workflows/deploy.yml al teu repositori i enganxa-hi tot aquest codi YAML amb les rutes i secrets ajustats.

✅ 5. Fes un push a master
Quan facis un push a la branca master, el workflow s'executarà automàticament i pujarà els fitxers.

*/
?>