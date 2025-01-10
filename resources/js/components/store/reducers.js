import { configureStore } from '@reduxjs/toolkit';
import menusReducer from '../services/MenusSliceService';

const reducers = configureStore({
    reducer: {
        menus: menusReducer,
    },
    middleware: (getDefaultMiddleware) => getDefaultMiddleware(),
});

export default reducers;
