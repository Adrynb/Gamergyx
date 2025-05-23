import React, { use } from "react";
import { useState, useEffect } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faThumbsUp } from "@fortawesome/free-solid-svg-icons";

export default function Mas_Gustados() {
    const [postsMasGustados, setPostsMasGustados] = useState([]);

    const handlePostClick = (post) => {
    console.log("Post clicado:", post);
};


    const cargarMasGustados = () => {
        fetch("http://localhost/gamergyx/paginas/comunidad/comunidad/public/API/mas_gustados.php", {
            credentials: 'include',
            mode: 'cors'
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then((data) => {
                setPostsMasGustados(data);
            })
            .catch((error) => console.error("Error cargando posts:", error));
    };

    useEffect(() => {
        cargarMasGustados();
    }, []);


    return (
        <div className="mas-gustados">
            <h2>Más Gustados</h2>
            <ul>
                {postsMasGustados.map((post) => (
                    <li key={post.id} onClick={() => handlePostClick(post)}>
                        <h3>{post.autor}</h3>
                        <img src={post.imagen} alt="Imagen del post" />
                        <p>{post.contenido}</p>
                        <button>
                            <FontAwesomeIcon icon={faThumbsUp} /> {post.likes}
                        </button>
                    </li>

                ))}
            </ul>
        </div>
    );
}