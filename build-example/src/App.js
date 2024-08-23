import logo from "./logo.svg";
import "./App.css";
import wp from "./WordPress_blue_logo.svg.png";

function App() {
  return (
    <div className="App">
      <header className="App-header">
        <img src={logo} className="App-logo" alt="logo" />
        <img
          src={wp}
          className=""
          alt="logo"
          style={{ position: "absolute", width: "45px", marginTop: "-100px" }}
        />
        <p>WP React Bridge</p>
        <a
          className="App-link"
          href="https://github.com/pascualmanuel/WP-React-Bridge"
          target="_blank"
          rel="noopener noreferrer"
        >
          Visit Github
        </a>
      </header>
    </div>
  );
}

export default App;
