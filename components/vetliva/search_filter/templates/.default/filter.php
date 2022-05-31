<div class="container">
  <div class="search_filter">
    <form action="" method="get" id="search_filter-form">
      <div class="search_filter-search">
        <input placeholder="<?php echo GetMessage('search_placeholder');?>"
        <?php if (isset($_GET['search'])): ?>
          value="<?php echo $_GET['search']; ?>"
        <?php endif ?>
        type="text" name="search">
        <button class="search_filter-search_button">
          <i class="fa fa-search" aria-hidden="true"></i>
        </button>
      </div>
      <div class="search_filter-filter">
        <label>
          <input <?php if (isset($_GET['filter']) and $_GET['filter'] == 'accomodation'): ?>
          checked="checked"
          <?php endif ?> type="radio" value="accomodation" name="filter">
          <span><?php echo GetMessage('accomodation');?></span>
        </label>
        <label>
          <input <?php if (isset($_GET['filter']) and $_GET['filter'] == 'sanatorium'): ?>
          checked="checked"
          <?php endif ?> type="radio"  value="sanatorium" name="filter">
          <span><?php echo GetMessage('sanatorium');?></span>
        </label>
        <label>
          <input <?php if (isset($_GET['filter']) and $_GET['filter'] == 'tours'): ?>
          checked="checked"
          <?php endif ?> type="radio"  value="tours" name="filter">
          <span><?php echo GetMessage('tours');?></span>
        </label>
        <label>
          <span><?php echo GetMessage('REGION');?>:</span>
          <select name="region">
            <option value="">---</option>
            <?php foreach ($search_filter->regions_list as $key => $value): ?>
              <option
              <?php if (isset($_GET['region']) and $_GET['region']===$value['ID']): ?>
                selected
                <?php endif ?> value="<?php echo $value['ID']; ?>" >
                <?php echo getTextLanguage($value['NAME'],$value['PROPERTY_NAME_BY_VALUE'],$value['PROPERTY_NAME_EN_VALUE']); ?>              
              </option>
            <?php endforeach ?>
          </select>
        </label>
        <label id="search_filter-city" style="display:none">
          <span><?php echo GetMessage('CITY');?>:</span>
          <select name="city">
            <option value="N">---</option>
            <?php foreach ($search_filter->cities_list as $key => $value): ?>
              <option data-region="<?php echo $value['PROPERTY_REGION_VALUE'] ?>"
                <?php if (isset($_GET['city']) and $_GET['city']===$value['ID']): ?>
                  selected
                  <?php endif ?> value="<?php echo $value['ID']; ?>">
                  <?php echo getTextLanguage($value['NAME'],$value['PROPERTY_NAME_BY_VALUE'],$value['PROPERTY_NAME_EN_VALUE']); ?>            
                </option>
              <?php endforeach ?>
            </select>
          </label>
        </div>
      </form>
    </div>