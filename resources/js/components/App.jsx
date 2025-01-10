import React, { useEffect, useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { fetchMenus } from './services/MenusSliceService';
import Filters from './partials/Filters';
import MenuList from './partials/MenuList';

const App = () => {
    const dispatch = useDispatch();
    const { menus, cuisines, pagination, status } = useSelector((state) => state.menus);

    const [cuisineSlug, setCuisineSlug] = useState('');
    const [guests, setGuests] = useState(1);
    const [selectedCuisine, setSelectedCuisine] = useState('all');

    useEffect(() => {
        dispatch(fetchMenus({ cuisineSlug, perPage: 9, page: 1 }));
    }, [dispatch, cuisineSlug]);

    const handleShowMore = () => {
        dispatch(fetchMenus({
            cuisineSlug,
            perPage: 9,
            page: pagination.current_page + 1,
        }));
    };

    const handleCuisineChange = (slug) => {
        setSelectedCuisine(slug);
        setCuisineSlug(slug === 'all' ? '' : slug);
    };

    return (
        <div className="menu-page">
            <h1>Set Menus</h1>
            <Filters
                cuisines={cuisines}
                onCuisineChange={handleCuisineChange}
                guests={guests}
                onGuestsChange={setGuests}
                selectedCuisine={selectedCuisine}
            />
            {status === 'loading' && <p>Loading...</p>}
            {status === 'failed' && <p>Error loading menus.</p>}
            <MenuList menus={menus} guests={guests} />
            <div className='show-more-container'>
                {pagination.current_page < pagination.last_page && (
                    <button onClick={handleShowMore}>Show More</button>
                )}
            </div>
        </div>
    );
};

export default App;
