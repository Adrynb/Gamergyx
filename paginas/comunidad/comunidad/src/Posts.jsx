import { useEffect, useState } from "react";

export default function Posts() {
    const [posts, setPosts] = useState([]);
    const [nuevoPost, setNuevoPost] = useState("");

    useEffect(() => {
        fetch("../public/API/obtener_post.php")
            .then((response) => response.json())
            .then((data) => {
                setPosts(data);
            });
    }, []);

    const handleSubmit = (e) => {
        e.preventDefault();
        fetch("../public/API/añadir_post.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ post: nuevoPost }),
        })
            .then((response) => response.json())
            .then((data) => {
                setNuevoPost("");
                fetch("../public/obtener_post.php")
                    .then(res => res.json())
                    .then(data => setPosts(data));
            });
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
                <button type="submit">Comentar</button>
            </form>
            <ul>
                {posts.map((post) => (
                    <li key={post.id}>{post.post}</li>
                ))}
            </ul>
        </div>
    );
}
