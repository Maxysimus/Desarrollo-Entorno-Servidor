const mostrarTabla = (event) => {
            event.preventDefault();
            const numero = Number(document.getElementById('numero').value);
            if (numero >= 0 && numero <= 10) {
                let tabla = document.getElementById('tabla');
                let tablaDividir = `<h2>Tabla de dividir del número
                ${numero}</h2>`;
                tablaDividir += '<ul>';
                for (let i = 1; i <= 10; i++) {
                    tablaDividir += `<li>${numero} / ${i} = ${numero /
                    i}</li>`;
                }
                tablaDividir += '</ul>';
                tabla.innerHTML = tablaDividir;
            } else {
                alert('El número introducido debe estar entre 0 y 10 (ambos inclusive');
                document.getElementById("numero").value = '';
            }
        }
