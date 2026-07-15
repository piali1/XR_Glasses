(() => {
  const scanCanvas = document.createElement("canvas");
  const scanContext = scanCanvas.getContext("2d", {
    willReadFrequently: true
  });

  let scanBusy = false;

  function decodeRegion(source, sourceX, sourceY, sourceWidth, sourceHeight) {
    if (typeof window.jsQR !== "function" || !scanContext) {
      return null;
    }

    const maximumSize = 1400;
    const scale = Math.min(
      1,
      maximumSize / Math.max(sourceWidth, sourceHeight)
    );

    const width = Math.max(1, Math.round(sourceWidth * scale));
    const height = Math.max(1, Math.round(sourceHeight * scale));

    scanCanvas.width = width;
    scanCanvas.height = height;

    scanContext.drawImage(
      source,
      sourceX,
      sourceY,
      sourceWidth,
      sourceHeight,
      0,
      0,
      width,
      height
    );

    const imageData = scanContext.getImageData(0, 0, width, height);

    return window.jsQR(
      imageData.data,
      width,
      height,
      { inversionAttempts: "attemptBoth" }
    );
  }

  function decodeVideoFrame(video) {
    const width = video.videoWidth;
    const height = video.videoHeight;

    if (!width || !height) {
      return null;
    }

    return decodeRegion(video, 0, 0, width, height);
  }

  function decodeImageCodes(image) {
    const imageWidth = image.naturalWidth || image.width;
    const imageHeight = image.naturalHeight || image.height;
    const detectedCodes = new Set();

    const fullResult = decodeRegion(
      image,
      0,
      0,
      imageWidth,
      imageHeight
    );

    if (fullResult?.data) {
      detectedCodes.add(fullResult.data.trim());
    }

    // Zusätzlich Bereiche prüfen, damit QR-Sheets auch in Safari funktionieren.
    for (const gridSize of [2, 3, 4, 5]) {
      const cellWidth = imageWidth / gridSize;
      const cellHeight = imageHeight / gridSize;
      const overlap = 0.12;

      for (let row = 0; row < gridSize; row++) {
        for (let column = 0; column < gridSize; column++) {
          const sourceX = Math.max(
            0,
            column * cellWidth - cellWidth * overlap
          );

          const sourceY = Math.max(
            0,
            row * cellHeight - cellHeight * overlap
          );

          const sourceWidth = Math.min(
            imageWidth - sourceX,
            cellWidth * (1 + overlap * 2)
          );

          const sourceHeight = Math.min(
            imageHeight - sourceY,
            cellHeight * (1 + overlap * 2)
          );

          const result = decodeRegion(
            image,
            sourceX,
            sourceY,
            sourceWidth,
            sourceHeight
          );

          if (result?.data) {
            detectedCodes.add(result.data.trim());
          }
        }
      }
    }

    return [...detectedCodes].filter(Boolean);
  }

  async function processDetectedCodes(codes) {
    const uniqueCodes = [...new Set(codes.map(code => code.trim()))]
      .filter(Boolean);

    if (uniqueCodes.length === 0) {
      materialResult.textContent =
        "No QR code found. Please use a clear QR image.";
      materialResult.className = "material-result error";
      return;
    }

    const expectedCodes = getExpectedMaterialCodes();

    if (uniqueCodes.length === 1) {
      await validateMaterialCode(uniqueCodes[0]);
      return;
    }

    const relevantCodes = uniqueCodes.filter(code =>
      expectedCodes.includes(code)
    );

    const newRelevantCodes = relevantCodes.filter(code =>
      !scannedMaterialCodes.has(code)
    );

    const ignoredCount = uniqueCodes.length - relevantCodes.length;

    if (relevantCodes.length === 0) {
      materialResult.textContent =
        "QR codes found, but none belong to the current workflow step.";
      materialResult.className = "material-result error";
      return;
    }

    for (const code of newRelevantCodes) {
      await validateMaterialCode(code);
    }

    const step = steps[currentStep];

    materialResult.textContent =
      `${newRelevantCodes.length} matching QR code(s) verified. ` +
      `${ignoredCount} QR code(s) from other steps ignored. ` +
      `Current status: ${scannedMaterialCodes.size}/${step.materials.length} verified.`;

    materialResult.className = "material-result success";

    updateMaterialScanDisplay();
    updateNextButtonState();
  }

  window.scanMaterial = async function () {
    const missingCodes = getExpectedMaterialCodes()
      .filter(code => !scannedMaterialCodes.has(code));

    if (missingCodes.length === 0) {
      processVerifiedMaterial();
      return;
    }

    if (!cameraView?.srcObject) {
      const manualCode = prompt(
        "Camera is not available. Enter the material code manually:"
      );

      if (manualCode) {
        await validateMaterialCode(manualCode.trim());
      }

      return;
    }

    if (typeof window.jsQR !== "function") {
      alert("Safari QR fallback could not be loaded.");
      return;
    }

    qrScannerBox.classList.remove("hidden");
    qrScannerStatus.textContent =
      "Scanning QR code... Missing: " + missingCodes.join(", ");

    if (qrScanInterval) {
      clearInterval(qrScanInterval);
    }

    qrScanInterval = setInterval(async () => {
      if (scanBusy) {
        return;
      }

      scanBusy = true;

      try {
        let detectedCode = null;

        if ("BarcodeDetector" in window) {
          try {
            const detector = new BarcodeDetector({
              formats: ["qr_code"]
            });

            const nativeCodes = await detector.detect(cameraView);

            if (nativeCodes.length > 0) {
              detectedCode = nativeCodes[0].rawValue.trim();
            }
          } catch (error) {
            console.debug("Native QR detection unavailable.", error);
          }
        }

        if (!detectedCode) {
          const fallbackResult = decodeVideoFrame(cameraView);
          detectedCode = fallbackResult?.data?.trim() || null;
        }

        if (detectedCode) {
          await validateMaterialCode(detectedCode);
        }
      } catch (error) {
        console.error(error);
        qrScannerStatus.textContent =
          "QR scan failed. Hold the QR code clearly in front of the camera.";
      } finally {
        scanBusy = false;
      }
    }, 500);
  };

  window.scanQrFromFile = async function (input) {
    const file = input.files?.[0];

    if (!file) {
      return;
    }

    const imageUrl = URL.createObjectURL(file);
    const image = new Image();

    image.onload = async () => {
      try {
        let detectedCodes = [];

        if ("BarcodeDetector" in window) {
          try {
            const detector = new BarcodeDetector({
              formats: ["qr_code"]
            });

            const nativeResults = await detector.detect(image);

            detectedCodes = nativeResults
              .map(result => result.rawValue.trim())
              .filter(Boolean);
          } catch (error) {
            console.debug("Native image detection unavailable.", error);
          }
        }

        if (detectedCodes.length === 0) {
          detectedCodes = decodeImageCodes(image);
        }

        await processDetectedCodes(detectedCodes);
      } catch (error) {
        console.error(error);
        materialResult.textContent =
          "Could not scan the uploaded QR image.";
        materialResult.className = "material-result error";
      } finally {
        URL.revokeObjectURL(imageUrl);
        input.value = "";
      }
    };

    image.onerror = () => {
      URL.revokeObjectURL(imageUrl);
      input.value = "";

      materialResult.textContent =
        "Could not load the uploaded QR image.";
      materialResult.className = "material-result error";
    };

    image.src = imageUrl;
  };
})();
