import { useEffect, useState } from "react";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faShop, faThumbsUp, faComment, faFeather, faXmark, faFile } from '@fortawesome/free-solid-svg-icons';
import Popup from "./componentes/funcionalidades/Popup";
import AdjuntarArchivo from "./componentes/funcionalidades/AdjuntarArchivo";
import './estilos/posts/posts.css';

// Componente principal
export default function Posts() {
    // Estados
    const [posts, setPosts] = useState([]);
    const [nuevoPost, setNuevoPost] = useState("");
    const [usuarioActual, setUsuarioActual] = useState(null);
    const [respuestas, setRespuestas] = useState({});
    const [mostrarRespuesta, setMostrarRespuesta] = useState(null);
    const [mostrarNuevoPost, setMostrarNuevoPost] = useState(false);
    const [imagenAdjunta, setImagenAdjunta] = useState(null);
    const [imagenesAdjuntasRespuestas, setImagenesAdjuntasRespuestas] = useState({});

    // Filtrar posts principales (sin padre)
    const postsPrincipales = posts.filter(post => post.id_padre === null);

    // Obtener respuestas de un post
    const obtenerRespuestas = (idPost) => posts.filter(post => post.id_padre === idPost);

    const cargarPosts = () => {
        fetch("http://ec2-44-213-37-94.compute-1.amazonaws.com/gamergyx/paginas/comunidad/comunidad/public/API/obtener_post.php", {
            credentials: 'include',
            mode: 'cors'
        })
            .then((response) => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
            .then((data) => setPosts(data))
            .catch((error) => console.error("Error cargando posts:", error));
    };

    // Eliminar un post
    const eliminarPost = (id) => {
        fetch("http://ec2-44-213-37-94.compute-1.amazonaws.com/gamergyx/paginas/comunidad/comunidad/public/API/eliminar_post.php", {
            method: "POST",
            credentials: 'include',
            mode: 'cors',
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id }),
        })
            .then((response) => response.json())
            .then(() => cargarPosts())
            .catch((error) => console.error("Error eliminando post:", error));
    };

    // Dar like a un post
    const megustaPost = (id) => {
        fetch("http://ec2-44-213-37-94.compute-1.amazonaws.com/gamergyx/paginas/comunidad/comunidad/public/API/megusta_post.php", {
            method: "POST",
            credentials: 'include',
            mode: 'cors',
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ idPost: id, usuarioActual }),
        })
            .then((response) => response.json())
            .then(() => cargarPosts())
            .catch((error) => console.error("Error dando like:", error));
    };

    // Obtener usuario actual
    const obtenerUsuarioActual = () => {
        fetch("http://ec2-44-213-37-94.compute-1.amazonaws.com/gamergyx/paginas/comunidad/comunidad/public/API/usuario_actual.php", {
            credentials: 'include',
            mode: 'cors'
        })
            .then((response) => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
            .then((data) => {
                if (data.status === "success") setUsuarioActual(data.data);
            })
            .catch((error) => console.error("Error obteniendo usuario:", error));
    };

    // Enviar respuesta a un post
    const responderPost = (contenido, idPadre) => {
        fetch("http://ec2-44-213-37-94.compute-1.amazonaws.com/gamergyx/paginas/comunidad/comunidad/public/API/anadir_post.php", {
            method: "POST",
            credentials: 'include',
            mode: 'cors',
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ contenido, id_padre: idPadre }),
        })
            .then((response) => response.json())
            .then(() => {
                cargarPosts();
                setMostrarRespuesta(null);
            })
            .catch((error) => console.error("Error respondiendo post:", error));
    };

    // Efecto al cargar la página
    useEffect(() => {
        cargarPosts();
        obtenerUsuarioActual();
    }, []);



    const handleSubmit = (e) => {
        e.preventDefault();
        fetch("http://44.213.37.94/gamergyx/paginas/comunidad/comunidad/public/API/anadir_post.php", {
            method: "POST",
            credentials: "include",
            mode: "cors",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ contenido: nuevoPost, imagen: imagenAdjunta }),
        })
            .then((response) => response.json())
            .then(() => {
                setNuevoPost("");
                setImagenAdjunta(null);
                cargarPosts();
                setMostrarNuevoPost(false);
            })
            .catch((error) => console.error("Error publicando post:", error));
    };

    return (
      <main class="posts-container">
    <h1>Posts</h1>
    <form class="post-form" onSubmit={handleSubmit}>
        <input
            type="text"
            className="post-input"
            value={nuevoPost}
            onChange={(e) => setNuevoPost(e.target.value)}
        />
        <button type="submit" className="post-submit">Publicar</button>
        <AdjuntarArchivo onFileSelect={setImagenAdjunta} />
        <div className="image-preview">
            {imagenAdjunta && (
                <img
                    src={imagenAdjunta}
                    alt="Previsualización de imagen"
                    className="preview-image"
                />
            )}
        </div>
    </form>

    <ul class="post-list">
        {postsPrincipales.map((post) => (
            <li key={post.id} className="post-item">
                {post.fotoPerfil && (
                    <img
                        src={`http://ec2-44-213-37-94.compute-1.amazonaws.com/gamergyx/assets/images/perfiles/${post.fotoPerfil}`}
                        alt="Foto de perfil"
                        className="profile-image"
                    />
                )}
                <strong className="post-author">{post.nombre}</strong>
                <p className="post-content">{post.contenido}</p>
                {post.imagen && (
                    <img
                        src={`http://ec2-44-213-37-94.compute-1.amazonaws.com/gamergyx/paginas/comunidad/comunidad/public/API/${post.imagen}`}
                        alt="Imagen del post"
                        className="post-image"
                    />
                )}
                <small className="post-date">{new Date(post.fecha_publicacion).toLocaleString()}</small>
                <div className="post-actions">
                    <button onClick={() => megustaPost(post.id)} className="action-button like-button">
                        <FontAwesomeIcon icon={faThumbsUp} />
                    </button>
                    {usuarioActual?.nombre === post.nombre && (
                        <button onClick={() => eliminarPost(post.id)} className="action-button delete-button">
                            <FontAwesomeIcon icon={faXmark} />
                        </button>
                    )}
                    <button onClick={() => setMostrarRespuesta(post.id)} className="action-button comment-button">
                        <FontAwesomeIcon icon={faComment} />
                    </button>
                </div>

                {mostrarRespuesta === post.id && (
                    <form
                        className="response-form"
                        onSubmit={(e) => {
                            e.preventDefault();
                            responderPost(respuestas[post.id], post.id);
                            setImagenAdjunta(null);
                        }}
                    >
                        <textarea
                            placeholder="Escribe tu respuesta..."
                            className="response-input"
                            value={respuestas[post.id] || ""}
                            onChange={(e) => setRespuestas({ ...respuestas, [post.id]: e.target.value })}
                        />
                        <button type="submit" className="response-submit">Enviar respuesta</button>
                        <AdjuntarArchivo onFileSelect={setImagenAdjunta} />
                        <div className="image-preview">
                            {imagenAdjunta && (
                                <img
                                    src={imagenAdjunta}
                                    alt="Previsualización de imagen de respuesta"
                                    className="preview-image"
                                />
                            )}
                        </div>
                    </form>
                )}

                <ul className="response-list">
                    {obtenerRespuestas(post.id).map((respuesta) => (
                        <li key={respuesta.id} className="response-item">
                            {respuesta.fotoPerfil && (
                                <img
                                    src={`http://ec2-44-213-37-94.compute-1.amazonaws.com/gamergyx/assets/images/perfiles/${respuesta.fotoPerfil}`}
                                    alt="Foto de perfil"
                                    className="response-profile-image"
                                />
                            )}
                            <strong className="response-author">{respuesta.nombre}</strong>
                            <p className="response-content">{respuesta.contenido}</p>
                            <small className="response-date">{new Date(respuesta.fecha_publicacion).toLocaleString()}</small>
                            <div className="response-actions">
                                {usuarioActual?.nombre === respuesta.nombre && (
                                    <button onClick={() => eliminarPost(respuesta.id)} className="action-button delete-button">
                                        <FontAwesomeIcon icon={faXmark} />
                                    </button>
                                )}
                                <button onClick={() => megustaPost(respuesta.id)} className="action-button like-button">
                                    <FontAwesomeIcon icon={faThumbsUp} />
                                </button>
                            </div>
                        </li>
                    ))}
                </ul>
            </li>
        ))}
    </ul>

    <button className="boton-flotante" onClick={() => setMostrarNuevoPost(true)} title="Crear nuevo post">
        <FontAwesomeIcon icon={faFeather} size="2x" />
    </button>

    <Popup isOpen={mostrarNuevoPost} onClose={() => {
        setMostrarNuevoPost(false);
        setImagenAdjunta(null);
        setNuevoPost("");
    }} className="popup">
        <form className="popup-form" onSubmit={handleSubmit}>
            {usuarioActual?.fotoPerfil && (
                <img
                    src={`http://ec2-44-213-37-94.compute-1.amazonaws.com/gamergyx/assets/images/perfiles/${usuarioActual.fotoPerfil}`}
                    alt="Foto de perfil"
                    className="profile-image"
                />
            )}
            <textarea
                placeholder="¿Qué estás pensando?"
                className="popup-input"
                value={nuevoPost}
                onChange={(e) => setNuevoPost(e.target.value)}
                rows={4}
            />
            <button type="submit" className="popup-submit">Publicar</button>
            <AdjuntarArchivo onFileSelect={setImagenAdjunta}/>
            <div className="image-preview">
                {imagenAdjunta && (
                    <img
                        src={imagenAdjunta}
                        alt="Previsualización de imagen"
                        className="preview-image"
                    />
                )}
            </div>
        </form>
    </Popup>
</main>
    );
}
