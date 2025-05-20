import { useEffect, useState } from "react";

export default function Posts() {
    const [posts, setPosts] = useState([]);
    const [nuevoPost, setNuevoPost] = useState("");

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


    useEffect(() => {
        cargarPosts();
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
            body: JSON.stringify({ contenido : nuevoPost }),
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
                {posts.map((post, idx) => (
                    <li key={idx}>
                        <strong>{post.nombre}</strong><br />
                        {post.contenido}<br />
                        <small>{new Date(post.fecha_publicacion).toLocaleString()}</small>
                    </li>
                ))}
            </ul>

        </div>
    );
}
