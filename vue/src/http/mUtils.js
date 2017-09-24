/**
 * 存储localStorage
 */
export const setStore = (name, content) => {
  if (name === '') return
  if (typeof content !== 'string') {
    content = JSON.stringify(content)
  }
  let val = window.localStorage.getItem(name)
  if (typeof val === 'undefined') {
    window.localStorage.setItem(name, content)
  }
  window.localStorage.removeItem(name)
  window.localStorage.setItem(name, content)
}

/**
 * 获取localStorage
 */
export const getStore = name => {
  if (name === '') return null
  let val = window.localStorage.getItem(name)
  return undefined === typeof val ? null : val
}

/**
 * 删除localStorage
 */
export const removeStore = name => {
  if (name === '') return
  window.localStorage.removeItem(name)
}

/**
 * 存储sessionStorage
 */
export const setSessionStore = (name, content) => {
  if (name === '') return
  if (typeof content !== 'string') {
    content = JSON.stringify(content)
  }
  let val = window.sessionStorage.getItem(name)
  if (typeof val === 'undefined') {
    window.sessionStorage.setItem(name, content)
  }
  window.sessionStorage.removeItem(name)
  window.sessionStorage.setItem(name, content)
}

/**
 * 获取sessionStorage
 */
export const getSessionStore = name => {
  if (name === '') return null
  let val = window.sessionStorage.getItem(name)
  return undefined === typeof val ? null : val
}

/**
 * 删除sessionStorage
 */
export const removeSessionStore = name => {
  if (name === '') return
  return window.sessionStorage.removeItem(name)
}
