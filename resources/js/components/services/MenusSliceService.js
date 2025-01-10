import { createSlice, createAsyncThunk } from '@reduxjs/toolkit';
import axios from 'axios';

// Async thunk for fetching menus and filters
export const fetchMenus = createAsyncThunk(
    'menus/fetchMenus',
    async ({ cuisineSlug, perPage, page }, { rejectWithValue }) => {
        try {

            const response = await axios.get('/api/menus', {
                params: { cuisineSlug, perPage, page },
            });

            return response.data;
        } catch (err) {

            return rejectWithValue(err.response.data);
        }
    }
);

// Creating a Redux slice for managing menu-related state
const MenusSliceService = createSlice({
    name: 'menus',
    initialState: {
        menus: [],
        cuisines: [],
        pagination: {
            total: 0,
            per_page: 9,
            current_page: 1,
            last_page: 0,
        },
        loading: false,
        error: null,
    },
    reducers: {
        // Reducer to update the current page in the pagination state
        setPage: (state, action) => {
            state.pagination.current_page = action.payload;
        },
    },
    extraReducers: (builder) => {
        builder
            // Handle the pending state when the fetchMenus thunk is triggered
            .addCase(fetchMenus.pending, (state) => {
                state.loading = true;
                state.error = null;
            })
            // Handle the fulfilled state when fetchMenus is successful
            .addCase(fetchMenus.fulfilled, (state, action) => {
                state.loading = false;
                // Check if cuisineSlug is 'all'
                if (action.payload.cuisineSlug === 'all') {
                    // Append the new menus if the cuisineSlug is 'all'
                    state.menus = [...state.menus, ...action.payload.data];
                } else {
                    // Otherwise, replace the existing menus with the new data
                    state.menus = action.payload.data;
                }
                state.cuisines = action.payload.filters.cuisines;
                state.pagination = action.payload.pagination;
            })
            // Handle the rejected state when fetchMenus fails
            .addCase(fetchMenus.rejected, (state, action) => {
                state.loading = false;
                state.error = action.payload;
            });
    },
});

// Exporting actions for use in components
export const { setPage } = MenusSliceService.actions;

// Selectors to access specific parts of the menus state
export const selectMenus = (state) => state.menus.menus;
export const selectCuisines = (state) => state.menus.cuisines;
export const selectPagination = (state) => state.menus.pagination;
export const selectLoading = (state) => state.menus.loading;
export const selectError = (state) => state.menus.error;

export default MenusSliceService.reducer;
