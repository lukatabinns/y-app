import './bootstrap';

import React from 'react';
import ReactDOM from 'react-dom/client';
import { Provider } from 'react-redux';
import App from './components/App';
import store from './components/store/reducers';

const RootComponent = () => (
    <Provider store={store}>
        <App />
    </Provider>
)

const root = ReactDOM.createRoot(document.getElementById('app'));
root.render(<RootComponent />);
