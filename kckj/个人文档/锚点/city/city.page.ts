import { Component, OnInit } from '@angular/core';
import {CityListService} from '../../../city-list.service';
@Component({
  selector: 'app-city',
  templateUrl: './city.page.html',
  styleUrls: ['./city.page.scss'],
})
export class CityPage implements OnInit {
  myCitys:Array<any>[];
  constructor(public myCity:CityListService) {
    this.myCitys=this.myCity.CityList;
  }

  ngOnInit() {
  }
  goTo(location: string): void {
    window.location.hash = ''; 
    window.location.hash = location;
  }
}
