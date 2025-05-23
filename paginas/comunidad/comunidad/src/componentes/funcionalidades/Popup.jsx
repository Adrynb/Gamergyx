import React from "react";
import '../../estilos/posts/popup.css';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faX, faXmark } from '@fortawesome/free-solid-svg-icons';

export default function Popup({ isOpen, onClose, children }) {
    if (!isOpen) return null;

    return (
        <div className="popup-overlay">
            <div className="popup-content">
                <button className="close-button" onClick={onClose}>
                    <span className="close-icon"><FontAwesomeIcon icon={faXmark}/></span>
                </button>
                {children}
            </div>
        </div>
    );



}