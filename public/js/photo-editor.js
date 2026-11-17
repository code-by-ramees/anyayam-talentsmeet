/**
 * Photo Frame Studio — Beginner-Friendly Client-Side Canvas Engine
 */

(function () {
    'use strict';

    const config = window.PHOTO_FRAME_CONFIG || {};
    let frameImg = null;
    let userImg = null;
    let isFrameLoaded = false;
    let isUserImageLoaded = false;

    // Photo Placement State
    let photoState = {
        x: 0,
        y: 0,
        scale: 1.0,
        initialScale: 1.0,
        initialX: 0,
        initialY: 0
    };

    // Drag Interaction
    let isDragging = false;
    let dragStartX = 0;
    let dragStartY = 0;
    let photoStartX = 0;
    let photoStartY = 0;

    // DOM Elements
    const canvas = document.getElementById('previewCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');

    const canvasWrapper = document.getElementById('canvasWrapper');
    const uploadOverlay = document.getElementById('uploadOverlay');
    const imageInput = document.getElementById('imageInput');
    const imageInputHidden = document.getElementById('imageInputHidden');
    const btnChangePhoto = document.getElementById('btnChangePhoto');
    const btnFitOpening = document.getElementById('btnFitOpening');
    const stageStatusBadge = document.getElementById('stageStatusBadge');

    // Controls
    const zoomSlider = document.getElementById('zoomSlider');
    const zoomPercentBadge = document.getElementById('zoomPercentBadge');
    const btnZoomIn = document.getElementById('btnZoomIn');
    const btnZoomOut = document.getElementById('btnZoomOut');
    const btnReset = document.getElementById('btnReset');
    const btnDownloadPng = document.getElementById('btnDownloadPng');
    const btnDownloadJpg = document.getElementById('btnDownloadJpg');
    const editorAlert = document.getElementById('editorAlert');
    const editorAlertText = document.getElementById('editorAlertText');

    function showError(message) {
        if (editorAlert && editorAlertText) {
            editorAlertText.textContent = message;
            editorAlert.style.display = 'block';
            editorAlert.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            alert(message);
        }
    }

    function clearError() {
        if (editorAlert) {
            editorAlert.style.display = 'none';
        }
    }

    function initCanvas() {
        if (!config.canvasWidth || !config.canvasHeight) {
            showError("Frame configuration is missing canvas dimensions. Please check frame settings in admin.");
            return;
        }

        canvas.width = config.canvasWidth;
        canvas.height = config.canvasHeight;

        // Render soft preview
        draw();

        // Load Frame Image
        if (config.imageUrl) {
            frameImg = new Image();
            
            // Only use crossOrigin if loading from a different origin
            try {
                var urlObj = new URL(config.imageUrl, window.location.href);
                if (urlObj.origin !== window.location.origin) {
                    frameImg.crossOrigin = 'anonymous';
                }
            } catch (e) {
                // Ignore URL parsing errors and load standard
            }

            frameImg.onload = function () {
                isFrameLoaded = true;
                clearError();
                draw();
            };

            frameImg.onerror = function () {
                // If it failed with crossOrigin, retry without crossOrigin
                if (frameImg.crossOrigin) {
                    frameImg.crossOrigin = null;
                    frameImg.src = config.imageUrl;
                    return;
                }
                console.error("Failed to load frame image from:", config.imageUrl);
                showError("Unable to load the frame image. Please ensure the frame file exists and is accessible.");
            };

            frameImg.src = config.imageUrl;
        } else {
            showError("No active photo frame is selected. Please activate a frame in the admin dashboard.");
        }
    }

    function handleFile(file) {
        clearError();

        if (!file) {
            showError("Please upload a photo.");
            return;
        }

        if (!file.type.startsWith('image/')) {
            showError("Please select a valid image.");
            return;
        }

        // Limit size to 15MB
        if (file.size > 15 * 1024 * 1024) {
            showError("Image size exceeds the allowed limit.");
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            const img = new Image();
            img.onload = function () {
                userImg = img;
                isUserImageLoaded = true;

                // Hide upload prompt & reveal change button
                if (uploadOverlay) uploadOverlay.style.display = 'none';
                if (canvasWrapper) canvasWrapper.classList.add('has-photo');
                if (btnChangePhoto) btnChangePhoto.style.display = 'inline-block';
                if (stageStatusBadge) {
                    stageStatusBadge.className = 'badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fw-semibold small';
                    stageStatusBadge.innerHTML = '<i class="bi bi-arrows-move me-1"></i> Step 2: Drag & Zoom your Photo';
                }

                calculateInitialPlacement();
                draw();
            };
            img.onerror = function () {
                showError("Unable to process image. Please try again.");
            };
            img.src = e.target.result;
        };
        reader.onerror = function () {
            showError("Unable to process image. Please try again.");
        };
        reader.readAsDataURL(file);
    }

    function calculateInitialPlacement() {
        if (!userImg) return;

        // Auto-scale to fill opening
        const scaleX = config.photoWidth / userImg.width;
        const scaleY = config.photoHeight / userImg.height;
        const coverScale = Math.max(scaleX, scaleY);

        photoState.scale = coverScale;
        photoState.initialScale = coverScale;

        // Center inside opening
        const scaledWidth = userImg.width * coverScale;
        const scaledHeight = userImg.height * coverScale;

        photoState.x = config.photoX + (config.photoWidth - scaledWidth) / 2;
        photoState.y = config.photoY + (config.photoHeight - scaledHeight) / 2;
        photoState.initialX = photoState.x;
        photoState.initialY = photoState.y;

        if (zoomSlider) zoomSlider.value = 1.0;
        updateZoomDisplays(1.0);
    }

    // Main Draw Function: Photo clipped to opening + Transparent PNG overlay on top
    function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // 1. Background
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        // 2. Draw user photo clipped to opening
        if (isUserImageLoaded && userImg) {
            ctx.save();
            ctx.beginPath();
            ctx.rect(config.photoX, config.photoY, config.photoWidth, config.photoHeight);
            ctx.clip();

            const drawWidth = userImg.width * photoState.scale;
            const drawHeight = userImg.height * photoState.scale;
            ctx.drawImage(userImg, photoState.x, photoState.y, drawWidth, drawHeight);
            ctx.restore();
        } else {
            // Placeholder text inside opening
            ctx.fillStyle = '#f8fafc';
            ctx.fillRect(config.photoX, config.photoY, config.photoWidth, config.photoHeight);
        }

        // 3. Draw Transparent PNG Frame above photo
        if (isFrameLoaded && frameImg) {
            ctx.drawImage(frameImg, 0, 0, canvas.width, canvas.height);
        }
    }

    function updateZoomDisplays(factor) {
        const percent = Math.round(factor * 100);
        if (zoomPercentBadge) zoomPercentBadge.textContent = percent + '%';
    }

    function setZoom(factor) {
        if (!userImg) return;

        factor = Math.min(Math.max(factor, 0.2), 3.5);
        const oldScale = photoState.scale;
        const newScale = photoState.initialScale * factor;

        // Center of opening
        const centerX = config.photoX + (config.photoWidth / 2);
        const centerY = config.photoY + (config.photoHeight / 2);

        const ratio = newScale / oldScale;
        photoState.x = centerX - (centerX - photoState.x) * ratio;
        photoState.y = centerY - (centerY - photoState.y) * ratio;
        photoState.scale = newScale;

        if (zoomSlider) zoomSlider.value = factor;
        updateZoomDisplays(factor);
        draw();
    }

    function getCanvasCoordinates(e) {
        const rect = canvas.getBoundingClientRect();
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;

        const scaleX = canvas.width / rect.width;
        const scaleY = canvas.height / rect.height;

        return {
            x: (clientX - rect.left) * scaleX,
            y: (clientY - rect.top) * scaleY
        };
    }

    function onPointerDown(e) {
        if (!isUserImageLoaded) return;

        isDragging = true;
        const coords = getCanvasCoordinates(e);
        dragStartX = coords.x;
        dragStartY = coords.y;
        photoStartX = photoState.x;
        photoStartY = photoState.y;

        if (e.type === 'touchstart') {
            e.preventDefault();
        }
    }

    function onPointerMove(e) {
        if (!isDragging || !isUserImageLoaded) return;

        const coords = getCanvasCoordinates(e);
        const deltaX = coords.x - dragStartX;
        const deltaY = coords.y - dragStartY;

        photoState.x = photoStartX + deltaX;
        photoState.y = photoStartY + deltaY;

        draw();

        if (e.type === 'touchmove') {
            e.preventDefault();
        }
    }

    function onPointerUp() {
        isDragging = false;
    }

    function onWheel(e) {
        if (!isUserImageLoaded) return;
        e.preventDefault();

        const currentFactor = parseFloat(zoomSlider.value);
        const delta = e.deltaY < 0 ? 0.08 : -0.08;
        setZoom(currentFactor + delta);
    }

    // High-Resolution Download
    function downloadImage(format) {
        clearError();

        if (!isUserImageLoaded) {
            showError("Please upload a photo.");
            return;
        }

        try {
            const exportCanvas = document.createElement('canvas');
            exportCanvas.width = config.canvasWidth;
            exportCanvas.height = config.canvasHeight;
            const expCtx = exportCanvas.getContext('2d');

            if (format === 'jpeg') {
                expCtx.fillStyle = '#ffffff';
                expCtx.fillRect(0, 0, exportCanvas.width, exportCanvas.height);
            } else {
                expCtx.clearRect(0, 0, exportCanvas.width, exportCanvas.height);
            }

            // Draw photo inside opening
            expCtx.save();
            expCtx.beginPath();
            expCtx.rect(config.photoX, config.photoY, config.photoWidth, config.photoHeight);
            expCtx.clip();

            const drawWidth = userImg.width * photoState.scale;
            const drawHeight = userImg.height * photoState.scale;
            expCtx.drawImage(userImg, photoState.x, photoState.y, drawWidth, drawHeight);
            expCtx.restore();

            // Draw Transparent PNG frame on top
            if (isFrameLoaded && frameImg) {
                expCtx.drawImage(frameImg, 0, 0, exportCanvas.width, exportCanvas.height);
            }

            const mimeType = format === 'jpeg' ? 'image/jpeg' : 'image/png';
            const extension = format === 'jpeg' ? 'jpg' : 'png';
            const dateStr = new Date().toISOString().replace(/[:.]/g, '-').slice(0, 19);
            const fileName = `framed-photo-${dateStr}.${extension}`;

            exportCanvas.toBlob(function (blob) {
                if (!blob) {
                    showError("Unable to download. Please try again.");
                    return;
                }

                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = fileName;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                setTimeout(() => URL.revokeObjectURL(url), 1000);
            }, mimeType, 0.95);

        } catch (err) {
            console.error(err);
            showError("Unable to download. Please try again.");
        }
    }

    function setupEventListeners() {
        if (imageInput) {
            imageInput.addEventListener('change', function (e) {
                if (e.target.files && e.target.files[0]) {
                    handleFile(e.target.files[0]);
                }
            });
        }

        if (imageInputHidden) {
            imageInputHidden.addEventListener('change', function (e) {
                if (e.target.files && e.target.files[0]) {
                    handleFile(e.target.files[0]);
                }
            });
        }

        if (btnChangePhoto) {
            btnChangePhoto.addEventListener('click', function () {
                if (imageInput) imageInput.click();
            });
        }

        if (canvasWrapper) {
            canvas.addEventListener('mousedown', onPointerDown);
            window.addEventListener('mousemove', onPointerMove);
            window.addEventListener('mouseup', onPointerUp);

            canvas.addEventListener('touchstart', onPointerDown, { passive: false });
            window.addEventListener('touchmove', onPointerMove, { passive: false });
            window.addEventListener('touchend', onPointerUp);

            canvas.addEventListener('wheel', onWheel, { passive: false });
        }

        if (zoomSlider) {
            zoomSlider.addEventListener('input', function (e) {
                setZoom(parseFloat(e.target.value));
            });
        }

        if (btnZoomIn) {
            btnZoomIn.addEventListener('click', function () {
                const current = parseFloat(zoomSlider.value);
                setZoom(current + 0.1);
            });
        }

        if (btnZoomOut) {
            btnZoomOut.addEventListener('click', function () {
                const current = parseFloat(zoomSlider.value);
                setZoom(current - 0.1);
            });
        }

        if (btnReset || btnFitOpening) {
            const handleReset = function () {
                if (!isUserImageLoaded) return;
                calculateInitialPlacement();
                draw();
            };
            if (btnReset) btnReset.addEventListener('click', handleReset);
            if (btnFitOpening) btnFitOpening.addEventListener('click', handleReset);
        }

        if (btnDownloadPng) {
            btnDownloadPng.addEventListener('click', function () {
                downloadImage('png');
            });
        }

        if (btnDownloadJpg) {
            btnDownloadJpg.addEventListener('click', function () {
                downloadImage('jpeg');
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        initCanvas();
        setupEventListeners();
    });

})();
