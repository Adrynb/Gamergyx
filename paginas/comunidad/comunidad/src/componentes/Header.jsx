import React, { useState, useEffect } from "react";

export default function Header() {
    const [usuario, setUsuarioActual] = useState(null);
    const [menuAbierto, setMenuAbierto] = useState(null);

    const BASE_URL = "http://localhost/gamergyx/";

    useEffect(() => {
        fetch(`${BASE_URL}paginas/comunidad/comunidad/public/API/usuario_actual.php`, {
            credentials: 'include',
            mode: 'cors'
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.status === "success") {
                    setUsuarioActual(data.data);
                }
            })
            .catch((err) => console.error("Error cargando usuario actual:", err));

        const handleClickOutside = (event) => {
            if (!event.target.closest(".menu_container")) {
                setMenuAbierto(null);
            }
        };

        document.addEventListener("click", handleClickOutside);
        return () => document.removeEventListener("click", handleClickOutside);
    }, []);

    const toggleMenu = (menuId) => {
        setMenuAbierto(prev => prev === menuId ? null : menuId);
    };

    return (
        <header>
            <h2 id="titulo_gamergyx">
                Gamer<span>gyx</span>
            </h2>

            <nav>
                <ul>
                    <li><a href={`${BASE_URL}paginas/inicio/inicio.php`}>Inicio</a></li>
                    <li><a href={`${BASE_URL}paginas/noticias/noticias.php`}>Noticias</a></li>
                    <li>
                        <a href="#">Plataformas</a>
                        <ul>
                            <li><a href={`${BASE_URL}paginas/plataformas/nintendo.php`}>Nintendo Switch</a></li>
                            <li><a href={`${BASE_URL}paginas/plataformas/playstation.php`}>PlayStation</a></li>
                            <li><a href={`${BASE_URL}paginas/plataformas/xbox.php`}>Xbox</a></li>
                            <li><a href={`${BASE_URL}paginas/plataformas/pc.php`}>PC</a></li>
                        </ul>
                    </li>
                    <li><a href="http://localhost:3000">Comunidad</a></li>
                    <li><a href={`${BASE_URL}paginas/contacto/contacto.php`}>Contacto</a></li>
                </ul>
            </nav>

            <section className="header-container-usuario">
                <div id="buscar_container">
                    <form action={`${BASE_URL}paginas/configuracion/buscarResultados.php`} method="GET" encType="multipart/form-data">
                        <input type="text" placeholder="Buscar..." id="buscar_input" name="buscar_input" />
                        <button type="submit" id="buscar_btn">
                            <img src={`${BASE_URL}assets/images/logos/lupa.png`} alt="lupa" />
                        </button>
                    </form>
                </div>

                <span id="usuario_dinero">{usuario ? usuario.monedero_virtual : 0}€</span>

                <section className="menu_container">
                    <img
                        src={usuario && usuario.fotoPerfil ? `${BASE_URL}assets/images/perfiles/${usuario.fotoPerfil}` : `${BASE_URL}assets/images/logos/usuario_icon.png`}
                        alt="usuario"
                        className="icon_config"
                        onClick={() => toggleMenu('usuario')}
                    />
                    <div className="menu_config" style={{ display: menuAbierto === 'usuario' ? 'block' : 'none' }}>
                        <ul>
                            <li><a href={`${BASE_URL}paginas/configuracion/editar_perfil.php`}>Editar Perfil</a></li>
                            <li><a href={`${BASE_URL}paginas/configuracion/mis_pedidos.php`}>Mis pedidos</a></li>
                            <li><a href={`${BASE_URL}paginas/configuracion/cerrar_sesion.php`}>Cerrar Sesión</a></li>
                        </ul>
                    </div>
                </section>

                <section className="menu_container">
                    <img
                        src={`${BASE_URL}assets/images/logos/carro-de-la-compra.png`}
                        alt="carro-de-la-compra"
                        id="icon_carrito"
                        onClick={() => toggleMenu('carrito')}
                    />
                    <div className="menu_config" id="menu_config_carrito" style={{ display: menuAbierto === 'carrito' ? 'block' : 'none' }}>
                        <ul>
                            <li><a href={`${BASE_URL}paginas/gestion_videojuegos/carrito.php`}>Carrito</a></li>
                            <li><a href={`${BASE_URL}paginas/gestion_videojuegos/favoritos.php`}>Favoritos</a></li>
                        </ul>
                    </div>
                </section>
            </section>
        </header>
    );
}
