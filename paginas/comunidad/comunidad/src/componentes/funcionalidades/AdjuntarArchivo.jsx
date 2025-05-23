// AdjuntarArchivo.jsx
import React, { useRef } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faFile } from "@fortawesome/free-solid-svg-icons";

export default function AdjuntarArchivo({ onFileSelect }) {
    const inputRef = useRef(null);

    const handleButtonClick = () => {
        inputRef.current.click();
    };

    const handleFileChange = async (e) => {
        const archivo = e.target.files[0];
        if (archivo) {
            const base64 = await convertirABase64(archivo);
            onFileSelect(base64); // Pasa la imagen codificada al padre
        }
    };

    const convertirABase64 = (archivo) => {
        return new Promise((resolve, reject) => {
            const lector = new FileReader();
            lector.readAsDataURL(archivo);
            lector.onload = () => resolve(lector.result);
            lector.onerror = (error) => reject(error);
        });
    };

    return (
        <>
            <input
                type="file"
                ref={inputRef}
                style={{ display: "none" }}
                onChange={handleFileChange}
                accept="image/*"
            />
            <button type="button" onClick={handleButtonClick}>
                <FontAwesomeIcon icon={faFile} />
            </button>
        </>
    );
}
