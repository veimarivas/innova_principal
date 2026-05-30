<div class="modal fade" id="uploadDocModal" tabindex="-1" aria-labelledby="uploadDocModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #9a4904, #df6a04); border: none;">
                <h5 class="modal-title text-white fw-bold" id="uploadDocModalLabel">
                    <i class="ri-file-upload-line me-2"></i>Subir Documento
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-3" style="font-size: 0.9rem;">Sube el documento requerido. Formatos aceptados: PDF, JPG, PNG.</p>
                
                <div class="upload-foto-drop" id="docDrop" onclick="document.getElementById('docInput').click()">
                    <i class="ri-upload-cloud-2-line d-block"></i>
                    <p class="mb-1 fw-semibold" style="font-size: .88rem;">Haz clic o arrastra el archivo</p>
                    <p class="text-muted mb-0" style="font-size: .76rem;">PDF, JPG o PNG — máximo 5 MB</p>
                </div>
                <input type="file" id="docInput" accept="application/pdf,image/jpg,image/jpeg,image/png"
                       class="d-none">
                
                <div id="docAlert" class="alert d-none mt-3 mb-0" role="alert" style="font-size: .83rem;"></div>
            </div>
            <div class="modal-footer border-0 pt-0 pb-4 px-4">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn-upload-foto" id="btnSubirDoc" disabled>
                    <i class="ri-save-line me-1"></i>Guardar Documento
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="previewDocModal" tabindex="-1" aria-labelledby="previewDocModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #9a4904, #df6a04); border: none;">
                <h5 class="modal-title text-white fw-bold" id="previewDocModalLabel">
                    <i class="ri-file-view-line me-2"></i>Ver Documento
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="min-height: 400px;">
                <iframe id="previewDocFrame" src="" style="width: 100%; height: 500px; border: none;"></iframe>
            </div>
            <div class="modal-footer border-0 pt-0 pb-4 px-4">
                <a href="" id="downloadDocLink" class="btn btn-success" download>
                    <i class="ri-download-line me-1"></i>Descargar
                </a>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="verifyDocModal" tabindex="-1" aria-labelledby="verifyDocModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #9a4904, #df6a04); border: none;">
                <h5 class="modal-title text-white fw-bold" id="verifyDocModalLabel">
                    <i class="ri-shield-check-line me-2"></i>Verificar Documento
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <i class="ri-checkbox-circle-line" style="font-size: 3rem; color: #10b981;"></i>
                <p class="mt-3 mb-0" style="font-size: 0.95rem;">
                    ¿Confirmas que este documento es válido y está verificado?
                </p>
            </div>
            <div class="modal-footer border-0 pt-0 pb-4 px-4 justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnConfirmVerify">
                    <i class="ri-check-line me-1"></i>Confirmar Verificación
                </button>
            </div>
        </div>
    </div>
</div>