import React from 'react';
import Posts from './Posts';
import Header from './componentes/Header.jsx';
import Footer from './componentes/Footer.jsx';
import './estilos/header-footer/header.css';
import './estilos/header-footer/footer.css';

function App() {
  return (
    <div className="App">
      <Header />
      <Posts />
    </div>
  );
}

export default App;