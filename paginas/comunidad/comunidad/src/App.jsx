import React from 'react';
import Posts from './Posts';
import Header from './componentes/Header.jsx';
import './estilos/header-footer/header.css';
import './estilos/menus/menu.js';

function App() {
  return (
    <div className="App">
      <Header />
      <Posts />
    </div>
  );
}

export default App;