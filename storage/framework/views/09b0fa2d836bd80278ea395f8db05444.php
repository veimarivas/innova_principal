<?php
    $p = auth()->user()->persona;
    $avatarUrl = ($p && $p->fotografia && file_exists(public_path('images/personas/' . $p->fotografia)))
        ? asset('images/personas/' . $p->fotografia)
        : asset('build/images/users/avatar-1.jpg');
?>

<div class="modal fade" id="uploadFotoModal" tabindex="-1" aria-labelledby="uploadFotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #9a4904, #df6a04); border: none;">
                <h5 class="modal-title text-white fw-bold" id="uploadFotoModalLabel">
                    <i class="ri-camera-line me-2"></i>Cambiar Foto de Perfil
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">

                <div class="text-center mb-4">
                    <img id="fotoPreview"
                         src="<?php echo e($avatarUrl); ?>"
                         alt="Preview"
                         class="upload-foto-preview">
                </div>

                <div class="upload-foto-drop" id="fotoDrop" onclick="document.getElementById('fotoInput').click()">
                    <i class="ri-upload-cloud-2-line d-block"></i>
                    <p class="mb-1 fw-semibold" style="font-size:.88rem;">Haz clic o arrastra tu imagen aquí</p>
                    <p class="text-muted mb-0" style="font-size:.76rem;">JPG, JPEG o PNG — máximo 2 MB</p>
                </div>
                <input type="file" id="fotoInput" accept="image/jpg,image/jpeg,image/png"
                       class="d-none">

                <div id="fotoAlert" class="alert d-none mt-3 mb-0" role="alert" style="font-size:.83rem;"></div>
            </div>
            <div class="modal-footer border-0 pt-0 pb-4 px-4">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn-upload-foto" id="btnSubirFoto" disabled>
                    <i class="ri-save-line me-1"></i>Guardar Foto
                </button>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp_82_12\htdocs\innova-ciencia-virtual\resources\views/admin/profile/modals/upload-foto.blade.php ENDPATH**/ ?>