var dutchTranslations = [
  { from: 'tablespoon', to: 'el' },
  { from: 'tbsp',       to: 'el' },
  { from: 'teaspoon',   to: 'tl' },
  { from: 'tsp',        to: 'tl' },
  { from: 'grams',      to: 'g' },
];

var unitToMetric = [
  { fromUnit: 'cup',    toUnit: 'ml',    factor: 236.588237 },
  { fromUnit: 'pint',   toUnit: 'ml',    factor: 473.176473 },
  { fromUnit: 'quart',  toUnit: 'ml',    factor: 946.352946 },
  { fromUnit: 'gallon', toUnit: 'l',     factor: 3.78541178 },
  { fromUnit: 'ounce',  toUnit: 'grams', factor: 28.3495231 },
  { fromUnit: 'oz',     toUnit: 'grams', factor: 28.3495231 },
  { fromUnit: 'lb',     toUnit: 'grams', factor: 453.59237 },
  { fromUnit: 'pound',  toUnit: 'grams', factor: 453.59237 },
  { fromUnit: 'lemon',  toUnit: 'grams', factor: 30 },
  { fromUnit: 'banana', toUnit: 'grams', factor: 120 }
];

var spoonToCup = [
  { spoon: 'teaspoon',   cup: 0.0208333333 },
  { spoon: 'tsp',        cup: 0.0208333333 },
  { spoon: 'tablespoon', cup: 0.0625 },
  { spoon: 'tbsp',       cup: 0.0625 }
];

var productCupToMetric  = [
  { id: 'baking soda',       grams: 220.8, names: [ 'baking soda' ] },
  { id: 'banana puree',      grams: 300,   names: [ 'banana puree' ] },
  { id: 'bread flour',       grams: 127,   names: [ 'bread flour' ] },
  { id: 'brown sugar',       grams: 200,   names: [ 'brown sugar' ] },
  { id: 'butter',            grams: 226.8, names: [ 'butter' ] },
  { id: 'chocolate chips',   grams: 160,   names: [ 'chocolate chips' ] },
  { id: 'chopped nuts',      grams: 150,   names: [ 'chopped nuts' ] },
  { id: 'cocoa',             grams: 118,   names: [ 'cocoa powder' ] },
  { id: 'corn starch',       grams: 128,   names: [ 'corn starch', 'cornstarch', 'maize' ] },
  { id: 'flour',             grams: 125,   names: [ 'flour', 'all-purpose flour' ] },
  { id: 'honey',             grams: 340,   names: [ 'honey' ] },
  { id: 'powdered sugar',    grams: 125,   names: [ 'icing sugar', 'powdered sugar', 'confectioner\'s sugar', "confectioners\' sugar" ] },
  { id: 'instant yeast',     grams: 151.2, names: [ 'instant yeast' ] },
  { id: 'kosher salt',       grams: 288,   names: [ 'kosher salt' ] },
  { id: 'macaroni',          grams: 140,   names: [ 'macaroni' ] },
  { id: 'margarine',         grams: 217,   names: [ 'margarine' ] },
  { id: 'mozzarella',        grams: 112,   names: [ 'mozzarella' ] },
  { id: 'oatmeal',           grams: 90,    names: [ 'oatmeal', 'oats', 'rolled oats' ] },
  { id: 'parmesan',          grams: 90,    names: [ 'parmesan' ] },
  { id: 'panko',             grams: 150,   names: [ 'panko' ] },
  { id: 'peanut butter',     grams: 250,   names: [ 'peanut butter' ] },
  { id: 'rice',              grams: 190,   names: [ 'rice' ] },
  { id: 'shortening',        grams: 205,   names: [ 'shortening' ] },
  { id: 'salt',              grams: 273,   names: [ 'table salt', 'salt' ] },
  { id: 'white sugar',       grams: 200,   names: [ 'white sugar', 'sugar' ] },
  { id: 'whole wheat flour', grams: 120,   names: [ 'whole wheat flour' ] },
  { id: 'yoghurt',           grams: 245,   names: [ 'yoghurt' ] }
];
  
var productNames = productCupToMetric.map(function(product) {
  var nameList = product.names.map(function(names) {
    return names;
  });
  return nameList.join('|');
});

var spoonTypes = spoonToCup.map(function(type) {
  return type.spoon;
});

var unitTypes = unitToMetric.map(function(type) {
  return type.fromUnit;
});


// Create regular expressions for matching Strings
var productGroupRegEx = productNames.join('|');
var spoonTypesRegEx   = spoonTypes.join('|');
var unitTypesRegEx    = unitTypes.join('|');



/**
 * Convert recipe measurements to metric and translate measurements to Dutch.
 * 
 * @param  {String} text - Recipe ingrediënt list.
 * @return {String}      - Returns recipe with converted ingrediënts list.
 */
function convert_recipe( text ) {
  // Return recipe with converted measurements
  var ingredientMeasurementRegEx = /([0-9\/\., ]{2,})([a-z]*)(.*)/gi;
  var newString = text.replace( ingredientMeasurementRegEx, convert_ingredients );

  // Return recipe with measurements in Dutch
  var translationRequiredRegEx = /teaspoons?|tablespoons?|tsps?|tbsp?|grams?/g;
  newString = newString.replace( translationRequiredRegEx, translate_to_dutch );

  return newString;
}


/**
 * Translate measurement to Dutch.
 * 
 * @param  {String} word - Regex match result.
 * @return {String}      - Return translation of String.
 */
function translate_to_dutch( word ) {
  for( var i = 0; i < dutchTranslations.length; i++ ) {
    if( word.match( dutchTranslations[i].from ) ) {
      return dutchTranslations[i].to;
    }
  }
}


/**
 * Convert ingredient measurements to metric measurements.
 * Spoon measurements are not converted unless product is butter.
 * 
 * @param  {String} match   - Regex match result.
 * @param  {String} amount  - Regex found amount.
 * @param  {String} unit    - Regex found unit.
 * @param  {String} product - Regex found product.
 * @return {String}         - Returns a String with the converted amount, unit and ingrediënt name.
 */
function convert_ingredients( match, amount, unit, product ) {
  var productTypeRegEx = new RegExp(productGroupRegEx, "i");
  var gramsAmount  = 0;

  unit    = unit.trim().toLowerCase();
  product = product.trim().toLowerCase();
  amount  = to_decimal_number( amount, unit );

  // Check if product conversion is needed and the unit is not spoons (except if it is butter in spoons)
  if( ( product && !unit.match( spoonTypesRegEx ) ) || ( product.match( 'butter' ) && unit.match( spoonTypesRegEx ) ) ) {

    if( product.match( 'butter' ) ) {
      if( unit.match( spoonTypesRegEx ) ) {
        // Convert butter measurement from spoons to cups
        amount = spoon_to_cup( amount, unit );
      } else if( unit.match( 'sticks?' ) ) {
        // Convert butter measurement from stick to cups
        amount = stick_to_cup( amount );
      }
    }

    // Convert product measurements from cup to grams
    var productType = product.match( productTypeRegEx );
    
    if( productType ) {
      amount = product_cup_to_grams( amount, productType[0] );
      return amount + ' grams ' + product;
    }
  }

  // If no product conversion was needed, convert to metric measurement or leave intact
  if( unit.match( unitTypesRegEx ) ) {
    return cup_to_metric( amount, unit, product );
  } else {
    return amount + ' ' + unit + ' ' + product;
  }
}


/**
 * Replace comma with a dot and convert fractions to decimal numbers.
 * 
 * @param  {String} amount - Unit amount.
 * @param  {String} unit   - Unit of measurement.
 * @return {float}         - Returns an amount as a decimal number.
 */
function to_decimal_number( amount, unit ) {
  var fractionRegEx = /([0-9]+)?.?([0-9]+)\/([0-9]+)/;
  var fraction      = amount.match( fractionRegEx );
  
  // Convert fractions to decimals. Do not decode fractions for spoon units.
  if( fraction && !unit.match( spoonTypesRegEx ) ) {
    var decimal  = 0;

    if( fraction[1] ) {
      decimal += Number(fraction[1]);
    }
      
    if( fraction[2] && fraction[3] ) {
      decimal += Number(fraction[2]) / Number(fraction[3]);
    }

    amount = decimal;
  } else {
    amount = amount.trim().replace( ',', '.' );
  }

  return amount;
}

/**
 * Convert unit to grams with the corresponding weight of the product.
 * 
 * @param  {String}    amount  - Unit amount.
 * @param  {String} product - Type of product e.g. butter.
 * @return {int}            - Returns the converted amount in grams.
 */
function product_cup_to_grams( amount, product ) {
  for( var i = 0; i < productCupToMetric.length; i++ ) {
    var productNamesRegEx = productCupToMetric[i].names.join('|');

    if( product.match( productNamesRegEx ) ) {
      return Math.round( amount * productCupToMetric[i].grams );
    }
  }
}


/**
 * Convert non-metric to metric measurement.
 * 
 * @param  {String} amount  - Unit amount.
 * @param  {String} unit    - Unit of measurement e.g. pounds.
 * @param  {String} product - Type of product e.g. water.
 * @return {String}         - Returns a String with the converted amount, unit and ingrediënt name.
 */
function cup_to_metric( amount, unit, product ) {
  for( var i = 0; i < unitToMetric.length; i++ ) {
    if( unit.match( unitToMetric[i].fromUnit ) ) {
      amount = amount * unitToMetric[i].factor;
      return Math.round( amount ) + ' ' + unitToMetric[i].toUnit + ' ' + product;
    }
  }
}


/**
 * Convert spoon measurements to cup.
 * 
 * @param  {String} amount - Unit amount.
 * @param  {String} spoon  - Type of spoon.
 * @return {int}           - Returns the converted unit amount.
 */
function spoon_to_cup( amount, spoon ) {
  for( var i = 0; i < spoonToCup.length; i++ ) {
    if( spoon.match( spoonToCup[i].spoon ) ) {
      amount = amount * spoonToCup[i].cup;

      return Math.round( amount * 100 ) / 100;
    }
  }
}


/**
 * Convert stick of butter to cup.
 * 
 * @param  {String} amount - Butter amount in sticks.
 * @return {int}           - Returns the converted butter amount.
 */
function stick_to_cup( amount ) {
  amount = amount * 0.5;

  return Math.round( amount * 100 ) / 100;
}


$(document).ready(function() {

  $('#input').on('keyup', function() {
    var input      = $('textarea[name=input]').val();
    var output     = convert_recipe(input);

    $('#output').text(output);
  });

});