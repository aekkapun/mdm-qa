import {
  DateProfile,
  EventStore,
  EventUiHash,
  DateSpan,
  EventInteractionState,
  DayTable,
  Duration,
  DateComponent,
  DateRange,
  Slicer,
  Hit,
  ComponentContext
} from '@fullcalendar/core'
import { default as DayGrid, DayGridSeg } from './DayGrid'

export interface SimpleDayGridProps {
  dateProfile: DateProfile | null
  dayTable: DayTable
  nextDayThreshold: Duration
  businessHours: EventStore
  eventStore: EventStore
  eventUiBases: EventUiHash
  dateSelection: DateSpan | null
  eventSelection: string
  eventDrag: EventInteractionState | null
  eventResize: EventInteractionState | null
  isRigid: boolean
}

export default class SimpleDayGrid extends DateComponent<SimpleDayGridProps> {

  dayGrid: DayGrid

  private slicer = new DayGridSlicer()

  constructor(dayGrid: DayGrid) {
    super(dayGrid.el)

    this.dayGrid = dayGrid
  }

  firstContext(context: ComponentContext) {
    context.calendar.registerInteractiveComponent(this, { el: this.dayGrid.el })
  }

  destroy() {
    super.destroy()

    this.context.calendar.unregisterInteractiveComponent(this)
  }

  render(props: SimpleDayGridProps, context: ComponentContext) {
    let { dayGrid } = this
    let { dateProfile, dayTable } = props

    dayGrid.receiveContext(context) // hack because context is used in sliceProps
    dayGrid.receiveProps({
      ...this.slicer.sliceProps(props, dateProfile, props.nextDayThreshold, context.calendar, dayGrid, dayTable),
      dateProfile,
      cells: dayTable.cells,
      isRigid: props.isRigid
    }, context)
  }

  buildPositionCaches() {
    this.dayGrid.buildPositionCaches()
  }

  queryHit(positionLeft: number, positionTop: number): Hit {
    let rawHit = this.dayGrid.positionToHit(positionLeft, positionTop)

    if (rawHit) {
      return {
        component: this.dayGrid,
        dateSpan: rawHit.dateSpan,
        dayEl: rawHit.dayEl,
        rect: {
          left: rawHit.relativeRect.left,
          right: rawHit.relativeRect.right,
          top: rawHit.relativeRect.top,
          bottom: rawHit.relativeRect.bottom
        },
        layer: 0
      }
    }
  }

}


export class DayGridSlicer extends Slicer<DayGridSeg, [DayTable]> {

  sliceRange(dateRange: DateRange, dayTable: DayTable): DayGridSeg[] {
    return dayTable.sliceRange(dateRange)
  }

}
