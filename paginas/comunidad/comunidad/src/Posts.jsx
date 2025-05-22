import { useEffect, useState } from "react";

export default function Posts() {
    const [posts, setPosts] = useState([]);
    const [nuevoPost, setNuevoPost] = useState("");
    const [usuarioActual, setUsuarioActual] = useState(null);
    const [respuestas, setRespuestas] = useState({});
    const [mostrarRespuesta, setMostrarRespuesta] = useState(null);

    const postsPrincipales = posts.filter(post => post.id_padre === null);
    const obtenerRespuestas = (idPost) => posts.filter(post => post.id_padre === idPost);

    const cargarPosts = () => {
        fetch("http://localhost/gamergyx/paginas/comunidad/comunidad/public/API/obtener_post.php", {
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
                setPosts(data);
            })
            .catch((error) => console.error("Error cargando posts:", error));
    };

    const eliminarPost = (id) => {
        fetch("http://localhost/gamergyx/paginas/comunidad/comunidad/public/API/eliminar_post.php", {
            method: "POST",
            credentials: 'include',
            mode: 'cors',
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                id
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                cargarPosts();
            })
            .catch((error) => console.error("Error eliminando post:", error));
    }


    const megustaPost = (id) => {
        fetch("http://localhost/gamergyx/paginas/comunidad/comunidad/public/API/megusta_post.php", {
            method: "POST",
            credentials: 'include',
            mode: 'cors',
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                idPost: id,
                usuarioActual: usuarioActual
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                cargarPosts();
            })
            .catch((error) => console.error("Error eliminando post:", error));
    }

    const obtenerUsuarioActual = () => {

        fetch("http://localhost/gamergyx/paginas/comunidad/comunidad/public/API/config/usuario_actual.php", {
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
                if (data.status === "success") {
                    setUsuarioActual(data.nombre);
                }
            })
            .catch((error) => console.error("Error cargando usuario actual:", error));

    }

    const responderPost = (contenido, idPadre) => {
        fetch("http://localhost/gamergyx/paginas/comunidad/comunidad/public/API/anadir_post.php", {
            method: "POST",
            credentials: 'include',
            mode: 'cors',
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ contenido, id_padre: idPadre }),
        })
            .then((response) => response.json())
            .then(() => {
                cargarPosts();
                setMostrarRespuesta(null);
            })
            .catch((error) => console.error("Error enviando respuesta:", error));
    };



    useEffect(() => {
        cargarPosts();
        obtenerUsuarioActual();
    }, []);




    const handleSubmit = (e) => {
        e.preventDefault();
        fetch("http://localhost/gamergyx/paginas/comunidad/comunidad/public/API/anadir_post.php", {
            method: "POST",
            credentials: 'include',
            mode: 'cors',
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ contenido: nuevoPost }),
        })
            .then((response) => response.json())
            .then((data) => {
                setNuevoPost("");
                cargarPosts();
            })
            .catch((error) => console.error("Error enviando post:", error));
    };


    return (
        <div>
            <h1>Posts</h1>
            <form onSubmit={handleSubmit}>
                <input
                    type="text"
                    value={nuevoPost}
                    onChange={(e) => setNuevoPost(e.target.value)}
                />
                <button type="submit">Publicar</button>
            </form>
            <ul>
                {postsPrincipales.map((post) => (
                    <li key={post.id}>
                        <strong>{post.nombre}</strong><br />
                        {post.contenido}<br />
                        <small>{new Date(post.fecha_publicacion).toLocaleString()}</small><br />
                        <button onClick={() => megustaPost(post.id)}>Me gusta</button>
                        {usuarioActual === post.nombre && (
                            <button onClick={() => eliminarPost(post.id)}>Eliminar</button>
                        )}
                        <button onClick={() => setMostrarRespuesta(post.id)}>Responder</button>

                        {mostrarRespuesta === post.id && (
                            <form onSubmit={(e) => {
                                e.preventDefault();
                                responderPost(respuestas[post.id], post.id);
                            }}>
                                <input
                                    type="text"
                                    value={respuestas[post.id] || ""}
                                    onChange={(e) => setRespuestas({ ...respuestas, [post.id]: e.target.value })}
                                />
                                <button type="submit">Enviar respuesta</button>
                            </form>
                        )}

                        <ul>
                            {obtenerRespuestas(post.id).map((respuesta) => (
                                <li key={respuesta.id} style={{ marginLeft: "20px" }}>
                                    <strong>{respuesta.nombre}</strong><br />
                                    {respuesta.contenido}<br />
                                    <small>{new Date(respuesta.fecha_publicacion).toLocaleString()}</small>
                                    {usuarioActual === respuesta.nombre && (
                                        <button onClick={() => eliminarPost(respuesta.id)}>Eliminar</button>
                                    )}
                                    <button onClick={() => megustaPost(respuesta.id)}>Me gusta</button>
                                </li>
                            ))}
                        </ul>
                    </li>
                ))}
            </ul>


        </div>
    );
}
