/* eslint-disable prettier/prettier */
import { RouteLocationNormalizedLoaded, useRoute } from "vue-router"
import { Ref, unref } from "vue"
import { MaybeRef } from "@vueuse/core"
import { useCurrentRouter } from "@/router"

/**
 * Returns the root route that opened the current detail page
 */
export function getRootRoutePath(route?: MaybeRef<RouteLocationNormalizedLoaded>): string {
  if (!route) {
    route = useRoute()
  } else {
    route = unref(route)
  }
  if (!route) {
    return ""
  }
  // If one of the parent routes has the meta field "isDetailPageParent" set to true, we want to use that
  // route as the one to redirect to after the detail page is closed
  let rootRoute = [...route.matched].reverse().find((routeEntry) => {
    return routeEntry.meta.isDetailPageParent
  })
  // If no parent has "isDetailPageParent" explicitly set to true, we just use the root route
  if (!rootRoute) {
    rootRoute = route.matched[0]
  }

  let path = useCurrentRouter().resolve({ name: rootRoute.name, params: route.params }).href

  if (route.query && Object.keys(route.query).length) {
    path += "?"
    path += Object.entries(route.query)
      .map(([key, value]) => {
        if (!value) {
          return ""
        }
        return `${key}=${encodeURIComponent(<string>value)}`
      })
      .filter((value) => !!value)
      .join("&")
  }
  return path
}

/**
 * Returns the root route that opened the current detail page
 */
export function getDetailPageOutletRoute(routeInput: Ref<RouteLocationNormalizedLoaded>): string {
  const route = unref(routeInput)
  if (!route) {
    return ""
  }

  // If one of the parent routes has the meta field "isDetailPageParent" set to true, we want to use that
  // route as the one to redirect to after the detail page is closed
  const rootRoute = [...route.matched].reverse().find((routeEntry) => {
    return !!routeEntry.meta.isDetailPageOutlet
  })
  if (!rootRoute) {
    console.error("Could not determine detail page outlet")
    return ""
  }
  return useCurrentRouter().resolve({ name: rootRoute.name, params: useCurrentRouter().currentRoute.value.params }).href
}
