import React from 'react';

const Filters = ({ cuisines, onCuisineChange, guests, onGuestsChange, selectedCuisine }) => {
    return (
        <>            
            <div className="guests-input-container">
                <input
                    type="number"
                    min="1"
                    value={guests}
                    onChange={(e) => onGuestsChange(Number(e.target.value))}
                    id="guests"
                    className="guests-input"
                />
                <label htmlFor="guests" className="guests-label">Number of Guests:</label>
            </div>
            
            <h3>filter:</h3>
            <ul>
                {/* Default "All" button, highlighted when selected */}
                <li>
                    <button
                        className={selectedCuisine === 'all' ? 'selected' : ''}
                        onClick={() => onCuisineChange('all')}
                    >
                        All
                    </button>
                </li>
                
                {/* List of cuisines */}
                {cuisines.map((cuisine) => (
                    <li key={cuisine.slug}>
                        <button
                            className={selectedCuisine === cuisine.slug ? 'selected' : ''}
                            onClick={() => onCuisineChange(cuisine.slug)}
                        >
                            {cuisine.name} ({cuisine.menus_count})
                        </button>
                    </li>
                ))}
            </ul>
        </>
    );
};

export default Filters;
