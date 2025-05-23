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

    // Filtrar posts principales (sin padre)
    const postsPrincipales = posts.filter(post => post.id_padre === null);

    // Obtener respuestas de un post
    const obtenerRespuestas = (idPost) => posts.filter(post => post.id_padre === idPost);

    // Cargar todos los posts desde la API
    const cargarPosts = () => {
        fetch("http://localhost/gamergyx/paginas/comunidad/comunidad/public/API/obtener_post.php", {
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
        fetch("http://localhost/gamergyx/paginas/comunidad/comunidad/public/API/eliminar_post.php", {
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
        fetch("http://localhost/gamergyx/paginas/comunidad/comunidad/public/API/megusta_post.php", {
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
    const obtenerUsuarioActual = async () => {
        await fetch("http://localhost/gamergyx/paginas/comunidad/comunidad/public/API/usuario_actual.php", {
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
        fetch("http://localhost/gamergyx/paginas/comunidad/comunidad/public/API/anadir_post.php", {
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

    // Publicar nuevo post
    const handleSubmit = (e) => {
        e.preventDefault();
        fetch("http://localhost/gamergyx/paginas/comunidad/comunidad/public/API/anadir_post.php", {
            method: "POST",
            credentials: 'include',
            mode: 'cors',
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ contenido: nuevoPost, imagen: imagenAdjunta }),
        })
            .then((response) => response.json())
            .then(() => {
                setNuevoPost("");
                setImagenAdjunta(null);
                cargarPosts();
            })
            .catch((error) => console.error("Error publicando post:", error));
    };

    return (
        <div>
            {/* Formulario de publicación directa */}
            <h1>Posts</h1>
            <form onSubmit={handleSubmit}>
                <input
                    type="text"
                    value={nuevoPost}
                    onChange={(e) => setNuevoPost(e.target.value)}
                />
                <button type="submit">Publicar</button>
                <AdjuntarArchivo onFileSelect={setImagenAdjunta} />
            </form>

            {/* Lista de posts */}
            <ul>
                {postsPrincipales.map((post) => (
                    <li key={post.id}>
                        {post.fotoPerfil && (
                            <img
                                src={`http://localhost/gamergyx/assets/images/perfiles/${post.fotoPerfil}`}
                                alt="Foto de perfil"
                                style={{ width: "50px", height: "50px" }}
                            />
                        )}
                        <strong>{post.nombre}</strong><br />
                        {post.contenido}<br />
                        {post.imagen && (
                            <img
                                src={`http://localhost/gamergyx/paginas/comunidad/comunidad/public/API/${post.imagen}`}
                                alt="Imagen del post"
                                id="imagen_post"
                                width={"200px"}
                                height={"200px"}
                            />
                        )}
                        <br />
                        <small>{new Date(post.fecha_publicacion).toLocaleString()}</small><br />

                        <button onClick={() => megustaPost(post.id)}><FontAwesomeIcon icon={faThumbsUp} /></button>
                        {usuarioActual?.nombre === post.nombre && (
                            <button onClick={() => eliminarPost(post.id)}><FontAwesomeIcon icon={faXmark} /></button>
                        )}
                        <button onClick={() => setMostrarRespuesta(post.id)}><FontAwesomeIcon icon={faComment} /></button>

                        {/* Formulario de respuesta */}
                        {mostrarRespuesta === post.id && (
                            <form onSubmit={(e) => {
                                e.preventDefault();
                                responderPost(respuestas[post.id], post.id);
                            }}>
                                <textarea
                                    placeholder="Escribe tu respuesta..."
                                    value={respuestas[post.id] || ""}
                                    onChange={(e) => setRespuestas({ ...respuestas, [post.id]: e.target.value })}
                                />
                                <button type="submit">Enviar respuesta</button>
                                <AdjuntarArchivo onFileSelect={setImagenAdjunta} />
                            </form>
                        )}

                        {/* Respuestas */}
                        <ul id="respuestas">
                            {obtenerRespuestas(post.id).map((respuesta) => (
                                <li key={respuesta.id} style={{ marginLeft: "20px" }}>
                                    {respuesta.fotoPerfil && (
                                        <img
                                            src={`http://localhost/gamergyx/assets/images/perfiles/${respuesta.fotoPerfil}`}
                                            alt="Foto de perfil"
                                            style={{ width: "30px", height: "30px" }}
                                        />
                                    )}
                                    <strong>{respuesta.nombre}</strong><br />
                                    {respuesta.contenido}<br />
                                    <small>{new Date(respuesta.fecha_publicacion).toLocaleString()}</small>
                                    {usuarioActual?.nombre === respuesta.nombre && (
                                        <button onClick={() => eliminarPost(respuesta.id)}><FontAwesomeIcon icon={faXmark} /></button>
                                    )}
                                    <button onClick={() => megustaPost(respuesta.id)}><FontAwesomeIcon icon={faThumbsUp} /></button>
                                </li>
                            ))}
                        </ul>
                    </li>
                ))}
            </ul>

            {/* Botón flotante */}
            <button className="boton-flotante" onClick={() => setMostrarNuevoPost(true)} title="Crear nuevo post">
                <FontAwesomeIcon icon={faFeather} size="2x" />
            </button>

            {/* Popup para nuevo post */}
            <Popup isOpen={mostrarNuevoPost} onClose={() => setMostrarNuevoPost(false)}>
                <form onSubmit={(e) => {
                    e.preventDefault();
                    fetch("http://localhost/gamergyx/paginas/comunidad/comunidad/public/API/anadir_post.php", {
                        method: "POST",
                        credentials: 'include',
                        mode: 'cors',
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ contenido: nuevoPost }),
                    })
                        .then((response) => response.json())
                        .then(() => {
                            setNuevoPost("");
                            cargarPosts();
                            setMostrarNuevoPost(false);
                        })
                        .catch((error) => console.error("Error publicando post:", error));
                }}>
                    {usuarioActual?.fotoPerfil && (
                        <img
                            src={`http://localhost/gamergyx/assets/images/perfiles/${usuarioActual.fotoPerfil}`}
                            alt="Foto de perfil"
                            style={{ width: "50px", height: "50px", borderRadius: "50%" }}
                        />
                    )}
                    <textarea
                        placeholder="¿Qué estás pensando?"
                        value={nuevoPost}
                        onChange={(e) => setNuevoPost(e.target.value)}
                        rows={4}
                        style={{ width: "70%", padding: "10px", marginBottom: "10px" }}
                    />
                    <button type="submit">Publicar</button>
                </form>
            </Popup>
        </div>
    );
}
