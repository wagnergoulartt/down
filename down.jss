function deleteSong(id) {
        if (confirm("Tem certeza que deseja excluir esta música?")) {
            window.location.href = "?action=delete&id=" + id;
        }
    }

    function editSong(id, nomeMusica, nomeDJ, url, downloads, destaque) {
        var editNomeMusica = prompt("Editar nome da música:", nomeMusica);
        var editNomeDJ = prompt("Editar nome do DJ/Produtor:", nomeDJ);
        var editUrl = prompt("Editar URL da música:", url);
        var editDownloads = prompt("Editar número de downloads:", downloads);
        var editDestaque = prompt("Editar destaque (1 para sim, 0 para não):", destaque);

        if (editNomeMusica !== null && editNomeDJ !== null && editUrl !== null && editDownloads !== null && editDestaque !== null) {
            var form = document.createElement("form");
            form.setAttribute("method", "post");
            form.setAttribute("action", "");

            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", "edit_id");
            hiddenField.setAttribute("value", id);
            form.appendChild(hiddenField);

            var editNomeMusicaField = document.createElement("input");
            editNomeMusicaField.setAttribute("type", "hidden");
            editNomeMusicaField.setAttribute("name", "edit_nome_musica");
            editNomeMusicaField.setAttribute("value", editNomeMusica);
            form.appendChild(editNomeMusicaField);

            var editNomeDJField = document.createElement("input");
            editNomeDJField.setAttribute("type", "hidden");
            editNomeDJField.setAttribute("name", "edit_nome_dj");
            editNomeDJField.setAttribute("value", editNomeDJ);
            form.appendChild(editNomeDJField);

            var editUrlField = document.createElement("input");
            editUrlField.setAttribute("type", "hidden");
            editUrlField.setAttribute("name", "edit_url");
            editUrlField.setAttribute("value", editUrl);
            form.appendChild(editUrlField);

            var editDownloadsField = document.createElement("input");
            editDownloadsField.setAttribute("type", "hidden");
            editDownloadsField.setAttribute("name", "edit_downloads");
            editDownloadsField.setAttribute("value", editDownloads);
            form.appendChild(editDownloadsField);

            var editDestaqueField = document.createElement("input");
            editDestaqueField.setAttribute("type", "hidden");
            editDestaqueField.setAttribute("name", "edit_destaque");
            editDestaqueField.setAttribute("value", editDestaque);
            form.appendChild(editDestaqueField);

            document.body.appendChild(form);
            form.submit();
        }
    }

    // Edita o destaque da música
    function toggleHighlight(id) {
        const songRow = document.querySelector(`tr[data-song-id='${id}']`);
        const currentHighlight = songRow.getAttribute('data-destaque');

        let newHighlight;
        if (currentHighlight === '1') {
            newHighlight = '0';
        } else {
            newHighlight = '1';
        }

        const formData = new FormData();
        formData.append('edit_id', id);
        formData.append('edit_destaque', newHighlight);

        fetch('/consulta_destaque.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                songRow.setAttribute('data-destaque', newHighlight);
                songRow.classList.toggle('highlighted-song');

                // Atualiza o valor do destaque no campo de edição
                const editDestaqueField = document.querySelector(`tr[data-song-id='${id}'] .edit-destaque`);
                editDestaqueField.textContent = newHighlight;
            } else {
                console.log('Erro ao atualizar o destaque da música.');
            }
        })
        .catch(error => {
            console.log('Erro ao comunicar com o servidor.');
        });
    }